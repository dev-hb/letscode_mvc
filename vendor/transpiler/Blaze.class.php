<?php


class Blaze {

    /**
     * @var array|null
     */
    private $data;
    /**
     * @var $variables string|null
     */
    private $variables;
    /**
     * @var $result string
     */
    private $result;
    /**
     * @var $view string|null
     */
    private $view;

    /**
     * Blaze constructor.
     * @param string|null $view
     * @param string|null $variables
     */
    public function __construct($view = null, $variables = null){
        $this->variables = $variables;
        $this->view = $view;
        if($view != null){
            $this->setData(Filer::getContent($view.Constants::$VIEW_SUFFIX));
        }
    }

    /**
     * Apply all Blaze transformations and set the result (HTML doc)
     * @return $this
     */
    public function transform(){
        // bind template if exists, otherwise return basic data
        $this->bindTemplate();
        // Regularize input data (delete useless spaces)
        $this->setResult($this->regularize());
        // Apply Blaze transformations (ps: transformers order affect the result)
        $this->handleFunction("{{env(");
        $this->handleFunction("{{get(");
        $this->handleFunction("{{route(");
        $this->handleVariables();
        // native functions
        $this->handleNativeFunction("contains(");
        $this->handleNativeFunction("strlen(");
        $this->handleNativeFunction("startswith(");
        $this->handleNativeFunction("match(");
        // statements
        $this->handleForEach();
        $this->handleIfStatements();
        // return converted HTML document
        return $this;
    }

    /**
     * Handle all variables and replace with value
     */
    public function handleVariables(){
        $vars = $this->variables;
        if($vars && is_array($vars) && count($vars) > 0){
            $content = $this->getResult();
            foreach ($vars as $key=>$var){
                if(! is_array($var)) $content = str_replace('$'.$key, $var, $content);
                else{
                    if(strpos($content, '$'.$key) !== false) print_r($var);
                    $content = str_replace('$'.$key, "", $content);
                }
            }
            $this->setResult($content);
        }
    }

    /**
     * Handle string manipulating functions
     * @param $func
     */
    public function handleNativeFunction($func){
        $content = $this->getResult();
        while(strpos($content, $func) !== false){
            $route_part = explode($func, $content)[1];
            $vars = explode(")", $route_part)[0];
            if($func == "contains(") {
                $parts = explode(",", $this->cleanString($vars));
                $value = strpos(trim($parts[0]), trim($parts[1])) !== false ? 1 : 0;
            }elseif($func == "strlen("){
                $value = strlen(trim($vars));
            }
            elseif($func == "startswith("){
                $parts = explode(",", $this->cleanString($vars));
                $value = strpos(trim($parts[0]), trim($parts[1])) === 0 ? 1 : 0;
            }elseif($func == "match("){
                $parts = explode(",", $this->cleanString($vars));
                $value = preg_match('/'.trim($parts[0]).'/', trim($parts[1])) ? 1 : 0;
            }
            else $value = 0;
            $content = str_replace($func."$vars)", $value, $content);
        }
        $this->setResult($content);
    }

    /**
     * Get the complete view content out from layout
     * @return string|string[]
     */
    private function bindTemplate(){
        $content = "letscode".$this->getData();
        // check if the view does not require a layout
        if(strpos($content, "@content(") === false) return $this->getData();
        // if the view had a layout content
        $layout_part = explode("@content(", $content)[1];
        $layout_name = explode(")", $layout_part)[0];
        $layout = $this->cleanString(explode(")", $layout_part)[0]);
        // get the content of view file
        $view_data = str_replace("@content($layout_name)", "", $this->getData());
        // get layout content
        $layout_content = Filer::getContent($layout.Constants::$VIEW_SUFFIX);
        // bind view to layout
        $layout_content = str_replace("@yield", $view_data, $layout_content);
        $this->setResult($layout_content);
    }

    /**
     * Transform route function inside blaze views
     * @param $keyword
     * @return mixed|string
     */
    private function handleFunction($keyword){
        $content = $this->getResult();
        while(strpos($content, $keyword) !== false){
            $route_part = explode($keyword, $content)[1];
            $route_name = explode(")", $route_part)[0];
            if($keyword == "{{route(")
                $value = Router::find($this->cleanString($route_name))->getRoutePath();
            elseif($keyword == "{{get(")
                $value = Request::get($this->cleanString($route_name));
            elseif($keyword == "{{env(")
                $value = Environment::get($this->cleanString($route_name));
            else $value = "";
            $content = str_replace($keyword."$route_name)}}", $value, $content);
        }
        $this->setResult($content);
    }

    /**
     * Transform if statements inside the view
     * @return mixed|string
     */
    private function handleIfStatements(){
        $content = $this->getResult();
        while(strpos($content, "{@if") !== false){
            $exp_part = explode("{@if", $content)[1];
            $exp_temp = explode("}", $exp_part)[0];
            $exp = trim($exp_temp);
            $eval = (new Math($exp))->evaluate();
            if($eval === null) Logger::log("You have a syntax error in @if block");
            $data_block = explode("}", $exp_part)[1];
            $block_content = $eval === true ? trim(explode("{@endif", $data_block)[0]) : "";
            $data_block = "{@if$exp_temp}" . explode("{@endif}", $data_block)[0] . "}";
            $content = str_replace($data_block, $block_content, $content);
        }
        $this->setResult($content);
    }

    /**
     * Transform and duplicate looped blocks
     * @return mixed|string
     */
    private function handleForEach(){
        $content = $this->getResult();
        while(strpos($content, "{@for") !== false){
            $exp_part = explode("{@for", $content)[1];
            $exp_temp = explode("}", $exp_part)[0];
            $source = trim(explode("as", $exp_temp)[0]);
            $target = trim(explode("as", $exp_temp)[1]);
            $data_block = explode("}", $exp_part)[1];
            if(! isset($this->variables[str_replace('$', "", $source)]))
                Logger::log("The variable $source is not defined!");
            $data = $this->variables[str_replace('$', "", $source)];
            $block_content_temp = trim(explode("{@end", $data_block)[0]);
            $block_content = "";
            foreach ($data as $item){
                $block_content .= str_replace($target, $item, $block_content_temp);
            }
            $data_block = "{@for$exp_temp}" . explode("{@end}", $data_block)[0] . "}";
            $content = str_replace($data_block, $block_content, $content);
        }
        $this->setResult($content);
    }

    private function regularize(){
        $content = $this->getResult();
        $content = preg_replace("/\{\s+/", "{", $content);
        $content = preg_replace("/\s+\}/", "}", $content);
        return $content;
    }

    /**
     * Return stripped string (delete " and ')
     * @param $str
     * @return string|string[]
     */
    public function cleanString($str){
        return str_replace("\"", "", str_replace("'", "", str_replace(".", "/",$str)));
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param mixed $view
     */
    public function setView($view)
    {
        $this->view = $view;
    }
}
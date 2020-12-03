<?php


class Blaze {

    /**
     * @var string|null
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
        $this->data = $variables;
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
        $this->handleEnvProperties();
        $this->handleGetParameters();
        $this->handleRoutes();
        $this->handleForEach();
        $this->handleIfStatements();

        // return converted HTML document
        return $this;
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
     * @return mixed|string
     */
    private function handleRoutes(){
        $content = $this->getResult();
        while(strpos($content, "{{route(") !== false){
            $route_part = explode("{{route(", $content)[1];
            $route_name = explode(")", $route_part)[0];
            $value = Router::find($this->cleanString($route_name))->getRoutePath();
            $content = str_replace("{{route($route_name)}}", $value, $content);
        }
        $this->setResult($content);
    }

    /**
     * Transform get params inside blaze views
     * @return mixed|string
     */
    private function handleGetParameters(){
        $content = $this->getResult();
        while(strpos($content, "{{get(") !== false){
            $get_part = explode("{{get(", $content)[1];
            $get_name = explode(")", $get_part)[0];
            $value = Request::get($this->cleanString($get_name));
            $content = str_replace("{{get($get_name)}}", $value, $content);
        }
        $this->setResult($content);
    }

    /**
     * Transform environment properties to their actual values
     * @return mixed|string
     */
    private function handleEnvProperties(){
        $content = $this->getResult();
        while(strpos($content, "{{env(") !== false){
            $property_part = explode("{{env(", $content)[1];
            $property_name = explode(")", $property_part)[0];
            $value = Environment::get($this->cleanString($property_name));
            $content = str_replace("{{env($property_name)}}", $value, $content);
        }
        $this->setResult($content);
    }

    /**
     * Transform if statements inside the view
     * @return mixed|string
     */
    private function handleIfStatements(){
        $content = $this->getResult();

        $this->setResult($content);
    }

    /**
     * Transform and duplicate looped blocks
     * @return mixed|string
     */
    private function handleForEach(){
        $content = $this->getResult();

        $this->setResult($content);
    }

    private function regularize(){
        $content = $this->getResult();
        $content = preg_replace("/\{\{\s+/", "{{", $content);
        $content = preg_replace("/\s+\}\}/", "}}", $content);
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
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
        $content = $this->bindTemplate();
        // Apply Blaze transformations (ps: transformers order affect the result)
        $this->handleRoutes();
        $this->handleForEach();
        $this->handleIfStatements();
        // set HTML result and return $this object
        $this->setResult($content);
        return $this;
    }

    /**
     * Get the complete view content out from layout
     * @return string|string[]
     */
    public function bindTemplate(){
        $content = "letscode".$this->getData();
        // check if the view does not require a layout
        if(strpos($content, "@content(") === false) return $this->getData();
        // if the view had a layout content
        $layout_part = explode("@content(", $content)[1];
        $layout_name = explode(")", $layout_part)[0];
        $layout = $this->getRoute(explode(")", $layout_part)[0]);
        // get the content of view file
        $view_data = str_replace("@content($layout_name)", "", $this->getData());
        // get layout content
        $layout_content = Filer::getContent($layout.Constants::$VIEW_SUFFIX);
        // bind view to layout
        $layout_content = str_replace("@yield", $view_data, $layout_content);
        return $layout_content;
    }

    /**
     * Transform route function inside blaze views
     * @return mixed|string
     */
    public function handleRoutes(){
        $content = $this->getResult();

        return $content;
    }

    /**
     * Transform if statements inside the view
     * @return mixed|string
     */
    public function handleIfStatements(){
        $content = $this->getResult();

        return $content;
    }

    /**
     * Transform and duplicate looped blocks
     * @return mixed|string
     */
    public function handleForEach(){
        $content = $this->getResult();

        return $content;
    }

    /**
     * Return stripped string (delete " and ')
     * @param $str
     * @return string|string[]
     */
    public function getRoute($str){
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
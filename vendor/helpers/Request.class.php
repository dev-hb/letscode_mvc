<?php


class Request {

    /**
     * @var $data
     */
    private static $data;
    private static $parameters = [];

    public function __construct(){
        static::$data = array_merge($_GET, $_POST);
    }

    /**
     * Returns GET object if exists, otherwise null
     * @param $key
     * @return array|null
     */
    public static function get($key){
        $all = array_merge($_GET, static::$parameters);
        if(! isset($all[$key])) return null;
        return $all[$key];
    }

    /**
     * Returns POST object if exists, otherwise null
     * @param $key
     * @return array
     */
    public static function post($key){
        if(! isset($_POST[$key])) return null;
        return $_POST[$key];
    }

    /**
     * @return mixed
     */
    public static function getData()
    {
        return self::$data;
    }

    /**
     * Forward the current route
     * @param $route
     * @return Route
     */
    public static function forward($route){
        Router::$current = $route;
        return Router::get(Router::$current);
    }

    /**
     * Redirect to a specific route
     * @param $route
     */
    public static function redirect($route){
        header("location: ?route=$route");
        exit;
    }

    /**
     * Bind new parameter from the URL
     * @param $name
     * @param $value
     */
    public static function bindParam($name, $value){
        static::$parameters[$name] = $value;
    }

}
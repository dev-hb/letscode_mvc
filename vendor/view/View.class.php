<?php


class View {
    /**
     * To insure that the handle method called just one time
     * @var bool $insure_onetime_call
     */
    private static $insure_onetime_call = false;
    private static $variables = [];

    /**
     * Get view by name or route
     * @param $view
     * @param $vars
     * @return mixed|string
     */
    public static function get($view, $vars = null){
        if(! Filer::viewExists($view) && $view) return Filer::getContent("404".Constants::$VIEW_SUFFIX);
        if($vars != null) static::$variables = array_merge(static::$variables, $vars);
        $blaze = new Blaze($view, static::$variables);
        return $blaze->transform()->getResult();
    }

    /**
     * Handle first time view (default home page view)
     * @param $view
     * @return mixed|string
     */
    public static function handle($view){
        if(static::$insure_onetime_call) return "";
        static::$insure_onetime_call = true;
        $v = Router::getRoute($view);
        // handle all the required middleware
        Router::bindMiddleware($v);
        Middleware::handle();
        $blaze = "";
        // check if the route controller exists or not
        if($v->getCallback() == null){
            if(! Filer::viewExists(Router::$current) && Router::$current)
                return Filer::getContent("404".Constants::$VIEW_SUFFIX);
            $blaze = new Blaze(Router::$current);
        }else{
            $callback = explode("@", $v->getCallback());
            $class = $callback[0];
            $method = count($callback) >= 2 ? $callback[1] : Constants::$DEFAULT_CONTROLLER_METHOD;
            $obj = new $class;
            return $obj->$method();
        }
        return $blaze->transform()->getResult();
    }

}
<?php


class Router {

    /**
     * Composition of routes
     * @var array
     */
    static $routes = [];
    static $current;

    /**
     * Creates a new route of method GET
     * @param $route
     * @param null $callback
     * @return Route
     */
    public static function get($route, $callback = null){
        $new_route = new Route($route, $callback);
        static::$routes[] = $new_route;
        return $new_route;
    }

    /**
     * Find a specific route by name
     * @param $name
     * @return mixed|Route
     */
    public static function find($name){
        // check if route exists with name otherwise create one and return it
        foreach (static::$routes as $route) {
            if ($route->getRoutePath() == $name) return $route;
            if ($route->getName() == $name) return $route;
        } return Request::forward("404");
    }

    /**
     * @param $name
     * @return mixed|Route
     */
    public static function getRoute($name){
        // check if route exists with name otherwise create one and return it
        foreach (static::$routes as $route) {
            if ($route->getRoutePath() == $name) return $route;
            if(strpos($route->getRoutePath(), "/") === false) continue;
            $nb_params = count(explode("/", $name));;
            if(count(explode("/", $route->getRoutePath())) != $nb_params) continue;
            $rt = explode("{", $route->getRoutePath())[0];
            if(strpos($name, $rt) !== false){
                // If route with parameters found then bind all params to Route object
                $params = explode("{", $route->getRoutePath());
                $values = explode("/", $name);
                unset($params[0]); // delete the non parameter entry
                // reverse params and values to start parsing from variables
                krsort($params);
                krsort($values);
                foreach ($params as $key=>$param){
                    $param = explode("}", $param)[0];
                    Request::bindParam($param, $values[$key]);
                }
                return $route;
            }
        }return Request::forward("404");
    }

    /**
     * Checks if a route name already exists or not
     * @param $name
     * @return bool
     */
    public static function checkRouteExistence($name){
        foreach (static::$routes as $route){
            if($route->getName() == $name) return true;
        } return false;
    }

    /**
     * Middleware : checks if route param exists or not (redirect to specific route)
     */
    public static function userProvidedRoute(){
        if(! Request::get(Constants::$ROUT_PARAM)) static::$current = Constants::$BASE_ROUTE;
        else static::$current = Request::get(Constants::$ROUT_PARAM);
    }

    /**
     * Init default middleware and add use ones
     * @param null $route
     */
    public static function bindMiddleware($route = null){
        Middleware::init();
        $r = $route == null ? Router::getRoute(Router::$current) : $route;
        if($r != null){
            foreach ($r->middlewares as $middleware){
                Middleware::bind($middleware);
            }
        }
    }
}
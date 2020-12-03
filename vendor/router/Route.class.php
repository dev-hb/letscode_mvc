<?php


class Route {

    /**
     * @var string|null $name
     */
    private $name;
    /**
     * @var string $route_path
     */
    private $route_path;
    /**
     * @var string|null $callback
     */
    private $callback;

    /**
     * @var array $middlewares
     */
    public $middlewares = [];

    /**
     * Route constructor.
     * @param string $route
     * @param string|null $callback
     */
    public function __construct($route, $callback=null)
    {
        $this->callback = $callback;
        $this->route_path = $route;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Route
     */
    public function name($name)
    {

        if(Router::checkRouteExistence($name)) Logger::log("The route $name has been already declared!");
        $this->name = $name;
        return $this;
    }

    /**
     * Bind middleware to route
     * @param $name string|Middleware
     */
    public function middleware($name){
        $name = trim($name);
        if($name != "")
            $this->middlewares[] = $name;
    }

    /**
     * @return string|null
     */
    public function getRoutePath()
    {
        return $this->route_path;
    }

    /**
     * @return mixed
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param mixed $callback
     * @return Route
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
        return $this;
    }

}
<?php


class Route {

    /**
     * @var string|null $name
     */
    private $name;
    /**
     * @var string $route
     */
    private $route;
    /**
     * @var string|null $callback
     */
    private $callback;

    /**
     * Route constructor.
     * @param string $route
     * @param string|null $callback
     */
    public function __construct($route, $callback=null)
    {
        $this->callback = $callback;
        $this->route = $route;
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
     * @return string|null
     */
    public function getRoute()
    {
        return $this->route;
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
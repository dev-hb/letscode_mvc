<?php


class Middleware {

    /**
     * Initialize all required middlewares
     * @var array
     */
    private static $middlewares = [
        "check_database_existence" => "Migrator@checkDatabaseExistence",
        "check_route_parameter" => "Router@userProvidedRoute"
    ];

    /**
     * Handle all middle wares
     */
    public static function handle(){
        // check database existence
        foreach (static::$middlewares as $middleware){
            $className = explode("@", $middleware)[0];
            $methodName = explode("@", $middleware)[1];
            $class = new $className;
            $class->$methodName();
        }
    }

    /**
     * Bind middleware by name
     * @param $name
     * @param $callback
     */
    public static function bind($name, $callback){
        static::$middlewares[$name] = $callback;
    }

}
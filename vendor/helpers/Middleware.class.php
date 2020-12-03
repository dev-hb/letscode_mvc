<?php


class Middleware {

    /**
     * Initialize all required middlewares
     * @var array
     */
    private static $middlewares = [];
    private static $loaded_middlewares = [];

    public static function init(){
        static::$middlewares = [
            "check_database_existence" => "Migrator@checkDatabaseExistence",
            "check_route_parameter" => "Router@userProvidedRoute"
        ];
    }

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
     * Load all user middlewares
     */
    public static function loadMiddlwares(){
        // check if user provided route, if not set current to home_page
        Router::userProvidedRoute();
        $dir = Constants::$MIDDLEWARES_DIR;
        $files = scandir($dir);
        foreach ($files as $file){
            if(! in_array($file, ['.', '..']) && strpos($file, 'Middleware.php') !== false){
                $class = str_replace(".php", "", $file);
                $name = strtolower(str_replace("Middleware", "", $class));
                if(! in_array("handleMiddleware", Reflect::getFunc($class)))
                    Logger::log("The middleware $name does not have handleMiddleware function.");
                if(Reflect::isDescendentOf($class, Middleware::class)){
                    static::$loaded_middlewares[$name] = "$class@handleMiddleware";
                }
            }
        }
    }

    /**
     * Bind middleware by name
     * @param $name
     */
    public static function bind($name){
        $name = str_replace("middleware", "", strtolower($name));
        $mw = isset(static::$loaded_middlewares[$name]) ? static::$loaded_middlewares[$name] : null;
        if($mw == null) Logger::log("The middleware $name does not exist or not loaded.");
        static::$middlewares[$name] = $mw;
    }

}
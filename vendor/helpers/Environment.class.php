<?php


class Environment {

    /**
     * @var array|null $properties
     */
    private static $properties = null;

    /**
     * Get all environment properties
     * @return array
     */
    public static function getProperties(){
        if(static::$properties != null) return static::$properties;
        $env_file = file_get_contents(".env");
        static::$properties = [];
        foreach (explode("\n", $env_file) as $l){
            $line = trim($l);
            if(strlen($line) == 0 || $line[0] == "#") continue;
            $parts = explode("=", $line);
            if(count($parts) == 1) {
                static::$properties[trim($parts[0])] = "";
                continue;
            }
            static::$properties[trim($parts[0])] = trim($parts[1]);
        }
        return static::$properties;
    }

    /**
     * Get specific property value
     * @param $name
     * @return mixed
     */
    public static function get($name){
        $props = Environment::getProperties();
        return $props[$name];
    }

}
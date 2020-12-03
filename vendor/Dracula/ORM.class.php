<?php


class ORM {

    /**
     * @var mysqli $conn
     */
    protected static $conn = null;

    /**
     * Returns a singleton MySQL connection
     * @return mysqli
     */
    public static function getConnection(){
        if(static::$conn != null) return static::$conn;
        $props = Environment::getProperties();
        static::$conn = new mysqli($props['database_hostname'], $props['database_username'],
        $props['database_password'], $props['database_dbname']);
        if(static::$conn->connect_errno == 1047) Logger::log("Database credentials (username/password) are incorrect");
        return static::$conn;
    }

}
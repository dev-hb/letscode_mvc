<?php


class Migrator {

    public static function checkDatabaseExistence(){
        try{
            $properties = Environment::getProperties()['database_dbname'];
            $conn = new mysqli(
                Environment::get("database_hostname"),
                Environment::get("database_username"),
                Environment::get("database_password")
            );
            if(Environment::get("database_dbname") == "") Logger::log("You must specify database name in .env file");
            $conn->query("CREATE DATABASE IF NOT EXISTS ". Environment::get("database_dbname"));
        }catch (Exception $e){
            echo $e->getMessage();
        }
    }

}
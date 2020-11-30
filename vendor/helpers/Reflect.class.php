<?php


class Reflect extends ReflectionClass {

    /**
     * Get all attributes of a given class name
     * @param $model
     * @return array|null
     */
    public static function getProps($model){
        $prop = [];
        try {
            $reflect = new ReflectionClass(ucfirst($model));
            $properties = $reflect->getProperties();
            foreach ($properties as $p) $prop[] = $p->name;
        } catch (ReflectionException $e) {
            return null;
        }
        return count($prop) ? $prop : null;
    }

    /**
     * Get all functions of a given class name
     * @param $model
     * @return array|null
     */
    public static function getFunc($model){
        $func = [];
        try {
            $reflect = new ReflectionClass(ucfirst($model));
            $functions = $reflect->getMethods();
            foreach ($functions as $f) $func[] = $f->name;
        } catch (ReflectionException $e) {
            return null;
        }
        return count($func) ? $func : null;
    }

}
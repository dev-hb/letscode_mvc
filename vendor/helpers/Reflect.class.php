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

    /**
     * Get a property that has a default value
     * @param $model
     * @param $property
     * @return string|null
     */
    public static function getDefaultValueOf($model, $property){
        try{
            $r = new ReflectionClass($model);
            $props = $r->getDefaultProperties();
            if(isset($props['name'])) return $props['name'];
        }catch (ReflectionException $e){
            Logger::log("Invalid middleware : " . $e->getMessage());
        }
        return null;
    }

    /**
     * Check if a class is inherited from another
     * @param $model
     * @param $class
     * @return bool
     */
    public static function isDescendentOf($model, $class){
        while($parent = get_parent_class($model) !== false){
            if($parent == $class) return true;
        } return false;
    }

}
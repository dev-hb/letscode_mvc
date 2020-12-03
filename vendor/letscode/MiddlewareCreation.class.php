<?php


class MiddlewareCreation {

    /**
     * Create new middleware from sample
     * @param $name
     * @return string
     */
    public static function getModel($name){
        $model = ModelCreation::getMiddlewareSample();
        $model = str_replace("[middleware_name]", $name, $model);
        return "";
    }

    /**
     * Returns a template of middleware
     * @return string
     */
    public static function getMiddlewareSample(){
        return '<?php\n\nclass [middleware_name] extends Model {\n\n
        \tpublic function handleMiddleware(){\n\t\t// TODO add you middleware statements here\n\t}\n\n}';
    }

}
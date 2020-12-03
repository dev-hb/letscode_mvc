<?php


class ModelCreation
{

    /**
     * Create new model from sample
     * @param $modelName
     * @return string
     */
    public static function getModel($modelName){
        $model = ModelCreation::getModelSample();
        $model = str_replace("[model_name]", $modelName, $model);
        return "";
    }

    /**
     * Returns a template of model
     * @return string
     */
    public static function getModelSample(){
        return '<?php\n\nclass [model_name] extends Model {\n\n
        \tprivate $id;\n[model_props]\n\n\tpublic function __construct($id=null){\n\t\t$this->id = $id;\n\t}\n\n' .
        "[model_getters_and_setters]\n\n}";
    }
}
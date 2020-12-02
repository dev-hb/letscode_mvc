<?php


class Filer {

    /**
     * Return the content of a file by path
     * @param $filepath
     * @return string
     */
    public static function getContent($filepath){
        if(! file_exists(Constants::$VIEWS_PATH . $filepath)){
            if(file_exists(Constants::$VIEWS_PATH."404".Constants::$VIEW_SUFFIX))
                return Filer::getContent("404".Constants::$VIEW_SUFFIX);
            return "<h3>Route Not Found : 404</h3><hr>The view that you want to access does not exists.<br>
Verify file name (Must be ".Constants::$VIEW_SUFFIX.")";
        }

        return file_get_contents(Constants::$VIEWS_PATH . $filepath);
    }

    /**
     * Return true if the view exists otherwise return false
     * @param $view
     * @return bool
     */
    public static function viewExists($view){
        return file_exists(Constants::$VIEWS_PATH . $view . Constants::$VIEW_SUFFIX);
    }
}
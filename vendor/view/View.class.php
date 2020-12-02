<?php


class View {

    public static function get($view){
        if(! Filer::viewExists($view)) return Filer::getContent("404".Constants::$VIEW_SUFFIX);
        $blaze = new Blaze($view);

        return $blaze->transform()->getResult();
    }

}
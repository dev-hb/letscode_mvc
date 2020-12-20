<?php


class ProductsController extends BaseController {

    public function getProduct(){
        $names = "Hello";
        return View::get('product', ['names' => $names]);
    }
}
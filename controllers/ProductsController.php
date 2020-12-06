<?php


class ProductsController extends BaseController {

    public function getProduct(){
        $name = "Zakaria HBA";
        return View::get('product', ['name' => $name]);
    }

}
<?php


class ProductsController extends BaseController {

    public function getProduct(){
        return View::get('product');
    }

}
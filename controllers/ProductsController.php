<?php


class ProductsController extends BaseController {

    public function getProduct(){
        $data = [
            "Bonjour",
            "Hello",
            "Khadija",
            "Zakaria"
        ];
        return View::get('product', ['names' => $data]);
    }

}
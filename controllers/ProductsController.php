<?php


class ProductsController extends BaseController {

    public function getProduct(){
        $data = [
            "name" => "Zakaria HBA"
        ];
        return View::get('product', ['data' => $data]);
    }

}
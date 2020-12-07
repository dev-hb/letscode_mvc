<?php


class ProductsController extends BaseController {

    public function getProduct(){
        $data = [
            "name" => "Zakaria HBA"
        ];
        return View::get('product', ['name' => $data['name']]);
    }

}
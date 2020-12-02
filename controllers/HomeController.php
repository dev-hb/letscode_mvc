<?php


class HomeController extends BaseController {

    public function index(){
        return View::get("home");
    }

}
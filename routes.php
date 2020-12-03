<?php

/// The routes a object that simplifies the redirection between pages
/// You can add new route by invoking get() or post() static methods

// Declaring web routes

Router::get("/letscode", "HomeController@index")->name('index');

Router::get("product/{ref}", "ProductsController@getProduct")->name('product');
Router::get("contact")->middleware(ContactMiddleware::class);
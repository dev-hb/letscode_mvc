<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once 'autoload.php';

// handle all the required middleware
Middleware::handle();

$user = new User();
$user->setEmail("bazaglo@gmail.coom");
$user->setUsername("bazbaza");
$user->setPassword("123456789");
$user->save();
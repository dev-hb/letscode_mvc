<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once 'autoload.php';
require_once 'routes.php';

// handle all the required middleware
Middleware::handle();

echo View::handle(Router::$current);
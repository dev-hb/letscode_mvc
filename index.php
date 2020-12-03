<?php
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once 'autoload.php';
// Load all user middleware
Middleware::loadMiddlwares();
// include user routes
require_once 'routes.php';

//$m = new Math("5 == 3");
//$m->evaluate();

echo View::handle(Router::$current);
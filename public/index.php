<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Check if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// 2. Register the Composer Autoloader...
require __DIR__.'/../vendor/autoload.php';

// 3. Bootstrap the application and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
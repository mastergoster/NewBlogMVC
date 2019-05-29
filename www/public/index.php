<?php
$basePath = dirname(__dir__) . DIRECTORY_SEPARATOR;

require_once $basePath . 'vendor/autoload.php';


$router = new App\Router($basePath . 'views');

$router->get('/', 'index', 'home')
    ->get('/categories', 'categories', 'categories')
    ->get('/article/[i:id]', 'post', 'post')
    ->run();

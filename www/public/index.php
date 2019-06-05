<?php
define("GENERATE_TIME_START", microtime(true));
$basePath = dirname(__dir__) . DIRECTORY_SEPARATOR;

require_once $basePath . 'vendor/autoload.php';

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

if (isset($_GET["page"]) && ((int)$_GET["page"] <= 1 || !is_int((int)$_GET["page"]) || is_float($_GET["page"] + 0))) {
    // url /categories?page=1&parm2=pomme
    if ((int)$_GET["page"] == 1) {
        $uri = explode('?', $_SERVER["REQUEST_URI"])[0];
        $get = $_GET;
        unset($get["page"]);
        $query = http_build_query($get);
        if (!empty($query)) {
            $uri = $uri . '?' . $query;
        }
        http_response_code(301);
        header('location: ' . $uri);
        exit();
    } else {
        throw new Exception('numero de page non valide ;) petit pirate');
    }
}

$router = new App\Router($basePath . 'views');

$router->get('/', 'index', 'home')
    ->get('/categories', 'categories', 'categories')
    ->get('/category/[*:slug]-[i:id]', 'category', 'category')
    ->get('/article/[*:slug]-[i:id]', 'post/index', 'post')
    ->run();

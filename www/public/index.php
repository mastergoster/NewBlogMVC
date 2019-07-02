<?php
$basePath = dirname(__dir__) . DIRECTORY_SEPARATOR;

require_once $basePath . 'vendor/autoload.php';

$app = App\App::getInstance();
$app->setStartTime();
$app::load();

$app->getRouter($basePath)
    ->get('/', 'beer#home', 'home')
    ->get('/boutique', 'beer#all', 'shop')
    ->get('/boutique/panier', 'cart#index', 'cart')
    ->get('/boutique/commande', 'beer#purchaseOrder', 'purchaseOrder')
    ->post('/boutique/commande', 'beer#purchaseOrder', 'getPurchaseOrder')
    ->get('/command/validation', 'command#validation', 'userForm')
    ->post('/command/validation', 'command#validation', 'validationCommande')
    ->get('/connexion', 'user#login', 'login')
    ->post('/connexion', 'user#login', 'connexion')
    ->get('/inscription', 'user#register', 'register')
    ->post('/inscription', 'user#register', 'registerValue')
    ->get('/user/profile', 'user#profile', 'userProfile')
    ->post('/user/updateUser', 'user#updateUser', 'updateUser')
    ->post('/user/changePassword', 'user#changePassword', 'updateUchangePasswordser')
    ->get('/user/logout', 'user#logout', 'logout')
    ->get('/contact', 'beer#contact', 'contact')
    ->post('/contact', 'beer#contact', 'sendMail')
    ->get('/blog', 'post#all', 'blog')
    ->get('/categories', 'Category#all', 'categories')
    ->get('/category/[*:slug]-[i:id]', 'Category#show', 'category')
    ->get('/article/[*:slug]-[i:id]', 'post#show', 'post')
    //AJAX URLS//
    ->post('/beer/find', 'beer#find', 'productToCart')
    ->get('/cart/getCart', 'cart#getProductsInCart', 'getProductInCart')
    ->post('/cart/update', 'cart#updateCart', 'updateCart')
    ->post('/cart/deleteLine', 'cart#delete', 'deleteCartLine')
    ->run();

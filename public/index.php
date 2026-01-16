<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';



use App\Core\Router;
use App\Controllers\AuthController;

$router = new Router();

$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'registerForm']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/test', [AuthController::class, 'testTwig']);

// Shop Routes
use App\Controllers\ShopController;
$router->get('/shop', [ShopController::class, 'index']);
$router->get('/shop/cart', [ShopController::class, 'cart']);
$router->post('/shop/add-to-cart', [ShopController::class, 'addToCart']);
$router->post('/shop/update-cart', [ShopController::class, 'updateCart']);
$router->post('/shop/remove-from-cart', [ShopController::class, 'removeFromCart']);
$router->get('/shop/checkout', [ShopController::class, 'checkout']);
$router->post('/shop/process-checkout', [ShopController::class, 'processCheckout']);
$router->get('/shop/purchase-result', [ShopController::class, 'purchaseResult']);


// Dashboard
use App\Controllers\DashboardController;
$router->get('/', [DashboardController::class, 'index']);

// Rewards Routes
use App\Controllers\RewardsController;
$router->get('/rewards', [RewardsController::class, 'index']);
$router->post('/rewards/redeem', [RewardsController::class, 'redeem']);

$router->dispatch();

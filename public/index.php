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

$router->get('/', function() {      
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
    echo "<h1>Welcome " . htmlspecialchars($_SESSION['user_name']) . "</h1>";
    echo "<a href='/logout'>Logout</a>";
});

$router->dispatch();

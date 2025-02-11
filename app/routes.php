<?php


use App\Controllers\AuthController;

// Home route
$router->get('/', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'register']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/dashboard', [AuthController::class, 'dashboard']);
$router->get('/logout', [AuthController::class, 'logout']);
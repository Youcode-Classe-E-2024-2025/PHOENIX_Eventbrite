<?php

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\AdminController;

// Home route
$router->get('/', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'register']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

// Profile route
$router->get('/profile', [AuthController::class, 'profile']);

// Dashboard route
$router->get('/dashboard', [DashboardController::class, 'dashboard']);
$router->get('/edit-user/:id', [AdminController::class, 'UpdateUser']);
$router->post('/edit-user/:id', [AdminController::class, 'UpdateUser']);
$router->post('/ban-user/:id', [AdminController::class, 'DeleteUser']);


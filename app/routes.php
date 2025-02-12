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


// Dashboard route
$router->get('/dashboard', [DashboardController::class, 'dashboard']);
$router->get('/edit-user/{id}', [AdminController::class, 'UpdateUser']);
$router->post('/edit-user/{id}', [AdminController::class, 'UpdateUser']);
// $router->get('/admin/events', [AdminController::class, 'totalEvents']);
// $router->get('/admin/events/pending', [AdminController::class, 'pendingEvents']);

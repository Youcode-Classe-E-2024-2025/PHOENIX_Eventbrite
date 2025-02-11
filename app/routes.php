<?php

use App\Controllers\AuthController;
use App\Controllers\DashboardController;

// Home route
$router->get('/', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'register']);
$router->post('/register', [AuthController::class, 'register']);
<<<<<<< HEAD


// Dashboard route
$router->get('/dashboard', [AuthController::class, 'dashboard']);
=======
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/dashboard', [DashboardController::class, 'dashboard']);
>>>>>>> origin/setup

<?php

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\AdminController;
use App\Controllers\EventController;

// Home route
$router->get('/', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'register']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

// Dashboard route
$router->get('/dashboard', [DashboardController::class, 'dashboard']);

// Event routes
// $router->get('/events/create', [EventController::class, 'create']);
// $router->post('/events/create', [EventController::class, 'store']);
// $router->get('/events/:id', [EventController::class, 'show']);
// $router->get('/events/:id/edit', [EventController::class, 'edit']);
// $router->post('/events/:id/update', [EventController::class, 'update']);
// $router->post('/events/:id/delete', [EventController::class, 'delete']);

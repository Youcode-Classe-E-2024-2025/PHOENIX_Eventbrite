<?php

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\AdminController;
use App\Controllers\PaticipantController;
use App\Controllers\PatticipantController;

// Home route
$router->get('/', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'register']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);


// Dashboard route
$router->get('/dashboard', [DashboardController::class, 'dashboard']);
// $router->get('/events',[PaticipantController::class, 'EventsPagination']);
$router->get('/events',[PaticipantController::class, 'Events']);
$router->get('/event_detail/:id', [PaticipantController::class, 'AccederEvent']);


//participant route 
// $router->get('/events',handler: [PaticipantController::class],'findAllEvent');

// $router->get('/admin/events', [AdminController::class, 'totalEvents']);
// $router->get('/admin/events/pending', [AdminController::class, 'pendingEvents']);

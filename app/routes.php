<?php

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\AdminController;
use App\Controllers\PaticipantController;
use App\Controllers\EventController;
use App\Controllers\ReservationController;

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
$router->get('/events/:page', [PaticipantController::class, 'EventsPagination']);
$router->get('/event_detail/:id', [PaticipantController::class, 'AccederEvent']);


//participant route 
// $router->get('/admin/events', [AdminController::class, 'totalEvents']);
$router->get('/resevation/:id', [ReservationController::class, 'ajouterReservation']);
// $router->get('/admin/events/pending', [AdminController::class, 'pendingEvents']);
$router->get('/edit-user/:id', [AdminController::class, 'UpdateUser']);
$router->post('/edit-user/:id', [AdminController::class, 'UpdateUser']);
$router->post('/ban-user/:id', [AdminController::class, 'DeleteUser']);

// Dashboard route
$router->get('/dashboard', [DashboardController::class, 'dashboard']);

// Event routes
$router->get('/events/create', [EventController::class, 'create']);
$router->post('/events/create', [EventController::class, 'store']);
// $router->get('/events/:id', [EventController::class, 'show']);
// $router->get('/events/:id/edit', [EventController::class, 'edit']);
// $router->post('/events/:id/update', [EventController::class, 'update']);
// $router->post('/events/:id/delete', [EventController::class, 'delete']);

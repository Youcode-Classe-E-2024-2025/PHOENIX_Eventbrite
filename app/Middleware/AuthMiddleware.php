<?php

namespace App\Middleware;
use App\Core\Session;

class AuthMiddleware {


    public function handle(){
        if (!isset($_SESSION['user_id'])) {
            Session::set('message', 'You must be logged in to access this page');
            header('Location: /');
            exit;
            
        }
    }

}
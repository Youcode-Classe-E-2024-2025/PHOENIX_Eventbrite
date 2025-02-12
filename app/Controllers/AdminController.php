<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Core\Validator;
use App\Models\User;

class AdminController extends Controller  // Changed from AuthController to Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    
}
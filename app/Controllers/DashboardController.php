<?php

namespace App\Controllers;

use App\Core\Controller;
class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

public function dashboard()
    {
        $case = $_SESSION['user_role'];
        switch ($case) {
            case 'Admin':
                $this->render('/admin-dashboard');
                break;
            case 'Organisateur':
                $this->render('/user-dashboard');
                break;
            default:
                $this->render('/Participant/index');
                break;
        }
    }
}
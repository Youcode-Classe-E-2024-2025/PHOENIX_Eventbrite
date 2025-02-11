<?php

namespace App\Controllers;

use App\Models\User;

use App\Core\Controller;
class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }



    public function totalUsers()
{
    $users = User::findAll();
    $totalUsers = count($users);
    
    return $totalUsers;
}



    public function dashboard()
    {
        $case = $_SESSION['user_role'];

        switch ($case) {
            case 'Admin':
                $dashboard = [
                    'totalUsers' => $this->totalUsers(),
                    'totalEvents' => 0, 
                    'pendingEvents' => [] 
                ];
                
                $this->render('Admin/index', ['dashboard' => $dashboard]);
                break;
            case 'Organisateur':
                $this->render('Organisateur/index');
                break;
            default:
                $this->render('Participant/index');
                break;
        }
    }
}
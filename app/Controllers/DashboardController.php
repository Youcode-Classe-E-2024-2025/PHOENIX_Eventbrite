<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Event;

use App\Core\Controller;

class DashboardController extends Controller
{
    private $Event;

    public function __construct()
    {
        parent::__construct();
    }


    public function AffichageEventsPracipant($id_user)
    {
        return $this->Event->SelectEventPraticiper($id_user);
    }



    public function totalUsers()
    {
        $users = User::findAll();
        $totalUsers = count($users);

        return $totalUsers;
    }
    public function TotalEvent()
    {
        $events = Event::findAllEvent();
        $totalEvents = count($events);
        return $totalEvents;
    }
    

    public function dashboard()
    {
        $case = $_SESSION['user_role'];
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        switch ($case) {
            case 'Admin':
                $dashboard = [
                    'totalUsers' => $this->totalUsers(),
                    'totalEvents' => $this->TotalEvent(),
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

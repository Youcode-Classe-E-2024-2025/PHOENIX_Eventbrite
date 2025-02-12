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


 
    public function AffichageDesEventes()
    {
        $events = Event::findAllEvent();
        return $events;
    }

    public function findAll()
    {
        $users = User::findAll();
        return $users;
    }

    public function totalUsers()
    {
        $users = User::findAll();
        $totalUsers = count($users);

        return $totalUsers;
    }

    public function PendingEvent()
    {
        $events = Event::getPendingEvent();
        return $events;
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
        switch ($case) {
            case 'Admin':
                $dashboard = [
                    'totalUsers' => $this->totalUsers(),
                    'totalEvents' => $this->TotalEvent(),
                    'pendingEvents' => $this->PendingEvent()
                ];
                $users = $this->findAll();
                $this->render('Admin/index', ['dashboard' => $dashboard, 'users' => $users]);
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

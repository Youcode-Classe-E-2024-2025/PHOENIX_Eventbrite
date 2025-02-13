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
        $this->Event = new Event();
    }


    public function AffichageEventsParticipant($id_user)
    {

        $events  = $this->Event->SelectEventPraticiper($id_user);
        return $events ;
    }


 
    public function AffichageDesEventes()
    {
        $events = Event::findAllEvent();
        return $events;
    }

    public function TotalEventParticper($id_user)
    {
        $events = $this->AffichageEventsParticipant($id_user);
        return count($events);
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
        $user = $_SESSION['user_id'];
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        switch ($case) {
            case 'Admin':
                $dashboard = [
                    'totalUsers' => $this->totalUsers(),
                    'totalEvents' => $this->TotalEvent(),
                    'pendingEvents' => $this->PendingEvent()
                ];


                // $this->render('Admin/index', ['dashboard' => $dashboard]);
                $users = $this->findAll();
                $this->render('Admin/index', ['dashboard' => $dashboard, 'users' => $users]);
                break;
            case 'Organisateur':
                $this->render('Organisateur/index');
                break;
                default:
                $eventsParticipe = $this->AffichageEventsParticipant($user);
                $dashboard_Participant = [
                    'events' => $eventsParticipe,
                    'countEventParticipe' => count($eventsParticipe)
                ];
            
                $this->render('Participant/index', $dashboard_Participant);
                break;
            
        }
    }


}

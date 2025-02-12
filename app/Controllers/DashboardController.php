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

    public function ticketSold()
    {
        $events = Event::ticketSold();
        return $events;
    }

    public function revenue()
    {
        $events = Event::revenue();
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
                $this->render('Admin/index', ['dashboard' => $dashboard]);
                break;
            case 'Organisateur':
                $dashboard = [
                    'totalEvents' => $this->TotalEvent(),
                    'ticketSold' => $this->ticketSold(),
                    'revenue' => $this->revenue(),
                ];
                var_dump($dashboard);
                $this->render('Organisateur/index', ['dashboard' => $dashboard]);
                break;
            default:
                $this->render('Participant/index');
                break;
        }
    }
}

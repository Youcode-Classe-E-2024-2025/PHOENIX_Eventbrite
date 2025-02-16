<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Event;
use App\Models\Reservation;
use PHPUnit\Framework\Constraint\Count;

class PaticipantController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function  findAllEvent()
    {
        $events = Event::findAllEvent();
        return $events;
    }
    public function accederEventReserver() {}
    public function Events()
    {
        $events = $this->findAllEvent();
        $this->render('Participant/events', ['events' => $events]);
    }

    public function getPaginationEvent($page)
    {
        $limit = 4;
        if (!is_numeric($page) || $page < 1) {
            $page = 1;
        }
        $offset = ($page - 1) * $limit;
        $events = Event::getPaginationEvent($limit, $offset);
        $totalEvents = count($this->findAllEvent());
        $totalPages = ceil($totalEvents / $limit);
        return [
            'events' => $events,
            'currentPage' => (int)$page,
            'totalPages' => $totalPages,
            'totalEvents' => $totalEvents
        ];
    }

    public function EventsPagination($page)
    {
        $events = $this->getPaginationEvent($page);
        $page = $events['currentPage'];
        $totalPages = $events['totalEvents'];
        $totalEvents = $events['totalEvents'];
        var_dump($page);
        if (!$events['events']) {
            throw new \Exception('Event not found', 404);
        }
        $this->render('Participant/events', ['events' => $events['events'], 'page' => $page, 'totalEvents' => $totalPages, 'totalEvents' => $totalEvents]);
    }

    public function AccederEvent($id)
    {
        $event = Event::selectEventById($id);
        if (!$event) {
            throw new \Exception('Event not found', 404);
        }

        $this->render('Participant/event_detail', ['event' => $event, 'id' => $id]);
    }
    
}

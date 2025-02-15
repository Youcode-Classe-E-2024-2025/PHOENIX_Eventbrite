<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Event;

class EventController extends Controller
{
    private Event $eventModel;

    public function __construct()
    {
        parent::__construct();
        $this->eventModel = new Event();
    }

    
    public function create()
    {
        
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Organisateur') {
            $this->redirect('/login');
        }

        $this->render('Organisateur/create_event');
    }

    
    public function store()
    {
         
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Organisateur') {
            $this->redirect('/login');
        }

        
        $title = $this->sanitizeInput($_POST['title'] ?? '');
        $description = $this->sanitizeInput($_POST['description'] ?? '');
        $date = $this->sanitizeInput($_POST['date'] ?? '');
        $time = $this->sanitizeInput($_POST['time'] ?? '');
        $location = $this->sanitizeInput($_POST['location'] ?? '');
        $capacity = filter_input(INPUT_POST, 'capacity', FILTER_VALIDATE_INT);
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $category = $this->sanitizeInput($_POST['category'] ?? '');
        $status = $this->sanitizeInput($_POST['status'] ?? '');
        $imageUrl = filter_input(INPUT_POST, 'image_url', FILTER_VALIDATE_URL);

        
        if (!$title || !$description || !$date || !$time || !$location || 
            !$capacity || !$price || !$category || !$status || !$imageUrl) {
            $_SESSION['error'] = 'All fields are required and must be valid';
            echo $_SESSION['error'];
            $this->render('Organisateur/create_event', [
                'title' => $title,
                'description' => $description,
                'date' => $date . ' ' . $time,
                'location' => $location,
                'capacity' => $capacity,
                'price' => $price,
                'category' => $category,
                'status' => $status,
                'image' => $imageUrl,
                'organizer_id' => $_SESSION['user_id']
            ]);
            return;
        }


        $newEvent = new Event(null, $title, $description, $date . ' ' . $time, $location, $price, $capacity, $_SESSION['user_id'], $status, $category);
        $newEvent->addEvent();
        
        
        
    }
    
    private function sanitizeInput(string $input): string
    {
        $input = trim($input);
        return htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public function getEvents() {
        header('Content-Type: application/json');
        $events = Event::getEvents();
        echo json_encode($events);
        exit;
    }
    
    public function renderEvents() {
        $countEvents = count(Event::findAllEvent());
        $countPages = ceil($countEvents / 4);
        $this->render('Participant/events', ['countPages' => $countPages,'countEvents' => $countEvents]);
    }
}
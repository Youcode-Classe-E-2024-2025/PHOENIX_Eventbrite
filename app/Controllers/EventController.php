<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Event;
use App\Models\Category;
use App\Models\Tag;

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
        // Vérifier si l'utilisateur est connecté et est un organisateur
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Organisateur') {
            $this->redirect('/login');
        }

        // Validation des données
        $title = $this->sanitizeInput($_POST['title'] ?? '');
        $description = $this->sanitizeInput($_POST['description'] ?? '');
        $date = $this->sanitizeInput($_POST['date'] ?? '');
        $time = $this->sanitizeInput($_POST['time'] ?? '');
        $location = $this->sanitizeInput($_POST['location'] ?? '');
        $capacity = filter_input(INPUT_POST, 'capacity', FILTER_VALIDATE_INT);
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $category = $this->sanitizeInput($_POST['category'] ?? '');
        $status = $this->sanitizeInput($_POST['status'] ?? '');


        // Validation de base
        if (!$title || !$description || !$date || !$time || !$location || !$capacity || !$price || !$category || !$status) {
            $_SESSION['error'] = 'All fields are required and must be valid';
            $this->redirect('/events/create');
            return;
        }

        // Gestion de l'upload d'image
        $imageUrl = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = dirname(dirname(__DIR__)) . '/public/uploads/events/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imageFileName = uniqid() . '_' . basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $imageFileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $imageUrl = '/uploads/events/' . $imageFileName;
            } else {
                $_SESSION['error'] = "Failed to upload image. Error: " . error_get_last()['message'];
                $this->redirect('/events/create');
                return;
            }
        } else {
            $_SESSION['error'] = "Image is required";
            $this->redirect('/events/create');
            return;
        }

        // Création de l'événement
        $newEvent = new Event(
            null,
            $title,
            $description,
            $date . ' ' . $time,
            $location,
            $price,
            $capacity,
            $_SESSION['user_id'],
            $status,
            $category,
            $imageUrl
        );

        if ($newEvent->addEvent()) {
            $eventId = Database::lastInsertId();
            // Gérer les tags
            if (isset($_POST['tags']) && is_array($_POST['tags'])) {
                Tag::addTagsToEvent($eventId, $_POST['tags']);
            }
            $this->redirect('/dashboard');
            $_SESSION['success'] = "Event created successfully.";
        } else {
            $_SESSION['error'] = "Error creating event.";
        }

    }

    public function update()
    {
        // Vérifier si l'utilisateur est connecté et est un organisateur
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Organisateur') {
            $this->redirect('/');
        }

        // Si c'est une requête GET, afficher le formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $id = htmlspecialchars($_GET['id']);
            $event = (new Event())->selectEventById($id);

            if (!$event) {
                $_SESSION['error'] = "Event not found.";
                $this->redirect('/dashboard');
            }
            $categories = Category::getAllCategories();
            $tags = Tag::getAllTags();
            $eventTags = Tag::getTagsByEventId($id);
            $this->render('Organisateur/update_event', [
                'event' => $event,
                'categories' => $categories,
                'tags' => $tags,
                'eventTags' => $eventTags
            ]);
            return;
        }
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $title = $this->sanitizeInput($_POST['title'] ?? '');
        $description = $this->sanitizeInput($_POST['description'] ?? '');
        $date = $this->sanitizeInput($_POST['date'] ?? '');
        $time = $this->sanitizeInput($_POST['time'] ?? '');
        $location = $this->sanitizeInput($_POST['location'] ?? '');
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $capacity = filter_input(INPUT_POST, 'capacity', FILTER_VALIDATE_INT);
        $status = $this->sanitizeInput($_POST['status'] ?? '');
        $category = $this->sanitizeInput($_POST['category'] ?? '');

        if (!$title || !$description || !$date || !$time || !$location || !$capacity || !$price || !$category || !$status) {
            $_SESSION['error'] = 'All fields are required and must be valid';
            $this->redirect('/events/create');
            return;
        }
        $imageUrl = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = dirname(dirname(__DIR__)) . '/public/uploads/events/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imageFileName = uniqid() . '_' . basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $imageFileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $imageUrl = '/uploads/events/' . $imageFileName;
            } else {
                $_SESSION['error'] = "Failed to upload image. Error: " . error_get_last()['message'];
                $this->redirect('/events/create');
                return;
            }
        }

        $event = new Event(
            $id,
            $title,
            $description,
            $date . ' ' . $time,
            $location,
            $price,
            $capacity,
            $_SESSION['user_id'],
            $status,
            $category,
            $imageUrl
        );

        if ($event->updateEvent()) {
            if (isset($_POST['tags'])) {
                Tag::removeTagsFromEvent($id);
                if (is_array($_POST['tags'])) {
                    Tag::addTagsToEvent($id, $_POST['tags']);
                }
            }
            $_SESSION['success'] = "Event updated successfully.";
            $this->redirect('/dashboard');
        } else {
            $_SESSION['error'] = "Error updating event.";
        }
    }

    
    public function delete()
    {
        $id = htmlspecialchars($_GET['id']);
        // Vérifier si l'utilisateur est connecté et est un organisateur
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Organisateur') {
            $this->redirect('/');
        }

        (new Event($id))->deleteEvent($id);
        $this->redirect('/dashboard');
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
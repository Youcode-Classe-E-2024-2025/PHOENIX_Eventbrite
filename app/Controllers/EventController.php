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

    /**
     * Affiche le formulaire de création d'événement
     */
    public function create()
    {
        // Vérifier si l'utilisateur est connecté et est un organisateur
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Organisateur') {
            $this->redirect('/login');
        }

        $this->render('Organisateur/create_event');
    }

    /**
     * Enregistre un nouvel événement
     */
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
        $imageUrl = filter_input(INPUT_POST, 'image_url', FILTER_VALIDATE_URL);

        // Validation de base
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

            $this->redirect('/dashboard');

            return;
        }

        // Création de l'événement
        $eventData = [
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
        ];

        var_dump($eventData);

        $newEvent = new Event(null, $title, $description, $date . ' ' . $time, $location, $price, $capacity, $_SESSION['user_id'], $status, $category);
        $newEvent->addEvent();
        // try {
        //     $this->eventModel->create($eventData);
        //     $_SESSION['success'] = 'Event created successfully';
        //     $this->redirect('/organizer/dashboard');
        // } catch (\Exception $e) {
        //     $_SESSION['error'] = 'Error creating event: ' . $e->getMessage();
        //     echo $_SESSION['error'];
        // }
    }

    /**
     * Sanitize input data
     */
    private function sanitizeInput(string $input): string
    {
        $input = trim($input);
        return htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Affiche le formulaire d'édition d'un événement
     */
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

            $this->render('Organisateur/update_event', ['event' => $event]);
            return;
        }

        // Si c'est une requête POST, traiter la mise à jour
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = htmlspecialchars($_POST['id']);
            $title = htmlspecialchars($_POST['title']);
            $description = htmlspecialchars($_POST['description']);
            $date = htmlspecialchars($_POST['date']);
            $time = htmlspecialchars($_POST['time']);
            $location = htmlspecialchars($_POST['location']);
            $price = htmlspecialchars($_POST['price']);
            $capacity = htmlspecialchars($_POST['capacity']);
            $status = htmlspecialchars($_POST['status']);
            $category = htmlspecialchars($_POST['category']);

            // Gestion de l'image
            $imageUrl = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '/uploads/events/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $imageFileName = uniqid() . '_' . basename($_FILES['image']['name']);
                $targetPath = $uploadDir . $imageFileName;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $imageUrl = '/uploads/events/' . $imageFileName;
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
                $_SESSION['success'] = "Event updated successfully.";
            } else {
                $_SESSION['error'] = "Error updating event.";
            }

            $this->redirect('/dashboard');
        }
    } 

    /**
     * Supprime un événement
     */
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
}

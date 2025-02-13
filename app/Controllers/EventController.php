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
            return;
        }

        // Création de l'événement
        // $eventData = [
        //     'title' => $title,
        //     'description' => $description,
        //     'date' => $date . ' ' . $time,
        //     'location' => $location,
        //     'capacity' => $capacity,
        //     'price' => $price,
        //     'category' => $category,
        //     'status' => $status,
        //     'image' => $imageUrl,
        //     'organizer_id' => $_SESSION['user_id']
        // ];

        // var_dump($eventData);

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
     * Affiche les détails d'un événement
     */
    public function show($id)
    {
        $event = $this->eventModel->findById($id);
        if (!$event) {
            $this->redirect('/organizer/dashboard');
        }

        $this->render('Organisateur/show_event', ['event' => $event]);
    }

    /**
     * Affiche le formulaire d'édition d'un événement
     */
    public function edit($id)
    {
         // Vérifier si l'utilisateur est connecté et est un organisateur
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Organisateur') {
            $this->redirect('/login');
        }

        $event = $this->eventModel->findById($id);
        if (!$event || $event['organizer_id'] !== $_SESSION['user_id']) {
            $this->redirect('/organizer/dashboard');
        }

        $this->render('Organisateur/edit_event', ['event' => $event]);
    }

    /**
     * Met à jour un événement
     */
    public function update($id)
    {
        // Similaire à store() mais pour la mise à jour
        // À implémenter selon les besoins
    }

    /**
     * Supprime un événement
     */
    public function delete($id)
    {
        // Vérifier si l'utilisateur est connecté et est un organisateur
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'Organisateur') {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized'], 403);
            return;
        }

        try {
            $success = $this->eventModel->deleteEvent($id, $_SESSION['user_id']);
            $this->jsonResponse(['success' => $success]);
        } catch (\Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Envoie une réponse JSON
     */
    private function jsonResponse($data, $status = 200)
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
}
<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\EventModel;

class EventController extends Controller
{
    private EventModel $eventModel;

    public function __construct()
    {
        parent::__construct();
        $this->eventModel = new EventModel();
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
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
        $time = filter_input(INPUT_POST, 'time', FILTER_SANITIZE_STRING);
        $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
        $capacity = filter_input(INPUT_POST, 'capacity', FILTER_VALIDATE_INT);
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
        $imageUrl = filter_input(INPUT_POST, 'image', FILTER_VALIDATE_URL);

        // Validation de base
        if (!$title || !$description || !$date || !$time || !$location || 
            !$capacity || !$price || !$category || !$status || !$imageUrl) {
            $_SESSION['error'] = 'All fields are required and must be valid';
            echo $_SESSION['error'];
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

        try {
            $this->eventModel->create($eventData);
            $_SESSION['success'] = 'Event created successfully';
            $this->redirect('/organizer/dashboard');
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error creating event: ' . $e->getMessage();
            echo $_SESSION['error'];
        }
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

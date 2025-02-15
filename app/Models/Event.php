<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Security;
use PDO;

class Event
{
    private $id;
    private $name;
    private $description;
    private $date;
    private $location;
    private $price;
    private $capacity;
    private $organizer_id;
    private $status;
    private $category_id;
    private $created_at;
    private $updated_at;
    private $tags = [];
    private static $limit = 4;
    // private $ContentVisuels = [];


    // Constructor remains the same
    public function __construct($id = '', $name = '', $description = '', $date = '', $location = '', $price = '', $capacity = '', $organizer_id = '', $status = '', $category_id = '')
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->date = $date;
        $this->location = $location;
        $this->price = $price;
        $this->capacity = $capacity;
        $this->organizer_id = $organizer_id;
        $this->status = $status;
        $this->category_id = $category_id;
        // $this->created_at = $created_at;
        // $this->updated_at = $updated_at;
        // $this->tags = $tags;
        // $this->ContentVisuels = $ContentVisuels;
    }

    public static function findAllEvent()
    {
        $requet = "SELECT * FROM events ORDER BY date DESC";
        $stmt = Database::getInstance()->prepare($requet);
        $stmt->execute();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $events ? $events : [];
    }

    public static function getPendingEvent()
    {
        $requet = "SELECT COUNT(*) as count from events where status = 'En attente'";
        $stmt = Database::getInstance()->prepare($requet);
        $stmt->execute();
        return $stmt->fetchColumn();
    }


    public static function findEventsByUserId($id)
    {
        $requet = "SELECT * FROM events where organizer_id = :id";
        $stmt = Database::getInstance()->prepare($requet);
        $stmt->execute([':id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function ticketSoldByUserId($id)
    {
        $requet = "SELECT sum(quantity) as quantity FROM reservations where user_id = :id";
        $stmt = Database::getInstance()->prepare($requet);
        $stmt->execute([':id' => $id]);
        return $stmt->fetchColumn();
    }

    public static function revenue($id)
    {
        $requet = "SELECT SUM(total_price) as revenue FROM reservations where user_id = :id";
        $stmt = Database::getInstance()->prepare($requet);
        $stmt->execute([':id' => $id]);
        return $stmt->fetchColumn();
    }

    public function addEvent()
    {
        $requet = "INSERT INTO events (title, description, date, location, price, capacity, organizer_id, status, category_id, created_at, updated_at) 
                   VALUES (:title, :description, :date, :location, :price, :capacity, :organizer_id, :status, :category_id, NOW(), NOW())";
        $stmt = Database::getInstance()->prepare($requet);
        return $stmt->execute([
            'title' => $this->name,
            'description' => $this->description,
            'date' => $this->date,
            'location' => $this->location,
            'price' => $this->price,
            'capacity' => $this->capacity,
            'organizer_id' => $this->organizer_id,
            'status' => $this->status,
            'category_id' => $this->category_id
        ]);
    }


    public static function selectEventById($id)
    {
        $requet = "SELECT * FROM events WHERE id = :id";
        $stmt = Database::getInstance()->prepare($requet);
        $stmt->execute(['id' => $id]);
        return $event = $stmt->fetch(\PDO::FETCH_ASSOC);
    }


    public function deleteEvent($id_event)
    {
        $event = $this->SelectEventById($id_event);
        $requet = "DELETE FROM events where id = :id";
        $stmt = Database::getInstance()->prepare($requet);
        $stmt->execute([
            ':id' => $id_event,
        ]);
        return $event['name'];
    }

    public function searchEvent() {}
    public  function SelectEventPraticiper($id_user)
    {
        $requet = "SELECT e.title , e.description , e.location, e.status, e.category_id ,e.status  FROM events AS e JOIN reservations AS c ON c.event_id = e.id where c.user_id = :id_user ORDER BY e.date DESC";
        $stmt = Database::getInstance()->prepare($requet);
        $stmt->execute([
            ':id_user' => $id_user,
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getEvents() {
        $page = $_GET['page'] ?? 1;
        $limit = $page * 4;
        $stmt = database::getInstance()->prepare("SELECT * FROM events LIMIT :limit");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
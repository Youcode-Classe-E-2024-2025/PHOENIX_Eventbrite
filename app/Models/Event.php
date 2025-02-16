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
    // private $ContentVisuels = [];
    private $image_url;

    // Constructor remains the same
    public function __construct($id = '', $name = '', $description = '', $date = '', $location = '', $price = '', $capacity = '', $organizer_id = '', $status = '', $category_id = '', $image_url = '')
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
        $this->image_url = $image_url;
        // $this->created_at = $created_at;
        // $this->updated_at = $updated_at;
        // $this->tags = $tags;
        // $this->ContentVisuels = $ContentVisuels;
    }
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getCapacity()
    {
        return $this->capacity;
    }

    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;
    }

    public function getOrganizerId()
    {
        return $this->organizer_id;
    }

    public function setOrganizerId($organizer_id)
    {
        $this->organizer_id = $organizer_id;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getCategoryId()
    {
        return $this->category_id;
    }

    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    public function getImageUrl()
    {
        return $this->image_url;
    }

    public function setImageUrl($image_url)
    {
        $this->image_url = $image_url;
    }

    public static function findAllEvent()
    {
        $requet = "SELECT * FROM events";
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
        $requet = "SELECT COALESCE(SUM(r.quantity), 0) as quantity 
                   FROM events e 
                   LEFT JOIN reservations r ON r.event_id = e.id 
                   WHERE e.organizer_id = :id AND r.status = 'Confirmé'";
        $stmt = Database::getInstance()->prepare($requet);
        $stmt->execute([':id' => $id]);
        return $stmt->fetchColumn();
    }

    public static function revenue($id)
    {
        $requet = "SELECT COALESCE(SUM(r.total_price), 0) as revenue 
                   FROM events e 
                   LEFT JOIN reservations r ON r.event_id = e.id 
                   WHERE e.organizer_id = :id AND r.status = 'Confirmé'";
        $stmt = Database::getInstance()->prepare($requet);
        $stmt->execute([':id' => $id]);
        return $stmt->fetchColumn();
    }

    public function addEvent()
    {
        $requet = "INSERT INTO events (title, description, date, location, price, capacity, organizer_id, status, category_id, created_at, updated_at, image_url) 
                   VALUES (:title, :description, :date, :location, :price, :capacity, :organizer_id, :status, :category_id, NOW(), NOW(), :image_url)";
        $stmt = Database::getInstance()->prepare($requet);
        $success = $stmt->execute([
            'title' => $this->name,
            'description' => $this->description,
            'date' => $this->date,
            'location' => $this->location,
            'price' => $this->price,
            'capacity' => $this->capacity,
            'organizer_id' => $this->organizer_id,
            'status' => $this->status,
            'category_id' => $this->category_id,
            'image_url' => $this->image_url
        ]);

        if ($success) {
            $this->id = Database::lastInsertId();
            return true;
        }
        return false;
    }

    public static function selectEventById($id)
    {
        $requet = "SELECT * FROM events WHERE id = :id";
        $stmt = Database::getInstance()->prepare($requet);
        $stmt->execute(['id' => $id]);
        return $event = $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function updateEvent()
    {
        $requet = "UPDATE events SET 
            title = :title,
            description = :description,
            date = :date,
            location = :location,
            price = :price,
            capacity = :capacity,
            status = :status,
            category_id = :category_id,
            image_url = COALESCE(:image_url, image_url),
            updated_at = NOW()
            WHERE id = :id";
            
        $stmt = Database::getInstance()->prepare($requet);
        return $stmt->execute([
            ':id' => $this->id,
            ':title' => $this->name,
            ':description' => $this->description,
            ':date' => $this->date,
            ':location' => $this->location,
            ':price' => $this->price,
            ':capacity' => $this->capacity,
            ':status' => $this->status,
            ':category_id' => $this->category_id,
            ':image_url' => $this->image_url
        ]);
    }

    public function deleteEvent($id_event)
    {
        $event = $this->selectEventById($id_event);
        $requet = "DELETE FROM events where id = :id";
        $stmt = Database::getInstance()->prepare($requet);
        $stmt->execute([
            ':id' => $id_event,
        ]);
        return $event['name'];
    }

    public static function getLastInsertedId()
    {
        return Database::getInstance()->lastInsertId();
    }
       public function SelectEventPraticiper($id_user){
            $requet = "SELECT e.title , e.description , e.location, e.status, e.category_id ,e.status  FROM events AS e JOIN reservations AS c ON c.event_id = e.id where c.user_id = :id_user";
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

 

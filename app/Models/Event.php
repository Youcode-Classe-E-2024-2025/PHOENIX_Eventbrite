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
    private $ContentVisuels = [];

    

    // Constructor remains the same
    public function __construct()
    {
        $this->id ;
        $this->name;
        $this->description ;
        $this->date;
        $this->location;
        $this->price;
        $this->capacity ;
        $this->organizer_id ;
        $this->status ;
        $this->category_id ;
        $this->created_at ;
        $this->updated_at ;
        $this->tags;
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



    public static function findAllEvent()
    {
        $requet = "SELECT * FROM events";
        $stmt = Database::getInstance()->prepare($requet);
        $stmt->execute();
        $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
     return $events ? $events : [];
    }
        
    public function addEvent()
    {
        $requet = "INSERT INTO event (name, description, date, location, price, capacity, organizer_id, status, category_id, created_at, updated_at) VALUES (:name,:description,:date,:location,:price,:capacity,:organizer_id,:status,:category_id,:created_at,:updated_at)";
        $stmt = Database::getInstance()->prepare($requet);
        return $stmt->execute([
            'name' => $this->name,
            'description' => $this->description,
            'date' => $this->date,
            'location' => $this->location,
            'price' => $this->price,
            'capacity' => $this->capacity,
            'organizer_id' => $this->organizer_id,
            'status' => $this->status,
            'category_id' => $this->category_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
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
    public  function SelectEventPraticiper($id_user){
            $requet = "SELECT e.title , e.description , e.location, e.status, e.category_id ,e.status  FROM events AS e JOIN reservations AS c ON c.event_id = e.id where c.user_id = :id_user";
            $stmt = Database::getInstance()->prepare($requet);
            $stmt->execute([
                ':id_user' => $id_user,
            ]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function getPaginationEvent($limit ,$offest){
        $requet = "SELECT * FROM events ORDER BY date  LIMIT = :LIMIT OFFSET = :OFFSET";
        $stmt =  $stmt = Database::getInstance()->prepare($requet);
        $stmt->execute([
            ':LIMIT' => $limit,
            ':OFFSET' => $offest
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}


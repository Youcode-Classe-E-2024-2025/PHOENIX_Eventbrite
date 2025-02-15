<?php

namespace App\Models;

use App\Core\Database;

class Tag
{
    private $id;
    private $name;

    public function __construct($id = null, $name = null)
    {
        $this->id = $id;
        $this->name = $name;
    }

    // Getters et Setters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function setId($id) { $this->id = $id; }
    public function setName($name) { $this->name = $name; }

    // Méthodes CRUD
    public static function getAllTags()
    {
        $query = "SELECT * FROM tags ORDER BY name";
        $stmt = Database::getInstance()->query($query);
        return $stmt->fetchAll();
    }

    public static function getTagsByEventId($eventId)
    {
        $query = "SELECT t.* FROM tags t 
                 INNER JOIN event_tags et ON t.id = et.tag_id 
                 WHERE et.event_id = :event_id";
        $stmt = Database::getInstance()->prepare($query);
        $stmt->execute(['event_id' => $eventId]);
        return $stmt->fetchAll();
    }

    public static function addTagsToEvent($eventId, $tagIds)
    {
        $db = Database::getInstance();
        $query = "INSERT INTO event_tags (event_id, tag_id) VALUES (:event_id, :tag_id)";
        $stmt = $db->prepare($query);
        
        foreach ($tagIds as $tagId) {
            $stmt->execute([
                'event_id' => $eventId,
                'tag_id' => $tagId
            ]);
        }
        return true;
    }

    public static function removeTagsFromEvent($eventId)
    {
        $query = "DELETE FROM event_tags WHERE event_id = :event_id";
        $stmt = Database::getInstance()->prepare($query);
        return $stmt->execute(['event_id' => $eventId]);
    }
}

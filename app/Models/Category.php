<?php

namespace App\Models;

use App\Core\Database;

class Category
{
    private $id;
    private $name;

    // Getters
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    // Méthodes CRUD
    public static function getAllCategories()
    {
        $query = "SELECT * FROM categories ORDER BY name";
        $stmt = Database::getInstance()->query($query);
        return $stmt->fetchAll();
    }

    public function getCategoryById($id)
    {
        $query = "SELECT * FROM categories WHERE id = :id";
        $stmt = Database::getInstance()->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function addCategory()
    {
        $query = "INSERT INTO categories (name) VALUES (:name)";
        $stmt = Database::getInstance()->prepare($query);
        return $stmt->execute([
            'name' => $this->name
        ]);
    }

    public function updateCategory()
    {
        $query = "UPDATE categories SET name = :name WHERE id = :id";
        $stmt = Database::getInstance()->prepare($query);
        return $stmt->execute([
            'id' => $this->id,
            'name' => $this->name
        ]);
    }

    public function deleteCategory()
    {
        $query = "DELETE FROM categories WHERE id = :id";
        $stmt = Database::getInstance()->prepare($query);
        return $stmt->execute([
            'id' => $this->id
        ]);
    }
}

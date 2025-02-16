<?php

namespace App\Core;

use PDO;

abstract class Model
{
   protected PDO $pdo;
   protected string $table;

   public function __construct()
   {
      $this->pdo = Database::getInstance();
   }

   public function getAll(): array
   {
      $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
      return $stmt->fetchAll();
   }

   public function getById(int $id): ?array
   {
      $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
      $stmt->execute(['id' => $id]);
      return $stmt->fetch() ?: null;
   }

   public function insert(array $data): bool
   {
      $columns = implode(", ", array_keys($data));
      $placeholders = ":" . implode(", :", array_keys($data));
      $stmt = $this->pdo->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
      return $stmt->execute($data);
   }

   public function update(int $id, array $data): bool
   {
      $set = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
      $data['id'] = $id;
      $stmt = $this->pdo->prepare("UPDATE {$this->table} SET $set WHERE id = :id");
      return $stmt->execute($data);
   }

   public function delete(int $id): bool
   {
      $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
      return $stmt->execute(['id' => $id]);
   }
}

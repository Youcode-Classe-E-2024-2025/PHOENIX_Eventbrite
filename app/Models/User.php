<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Security;

class User
{
    private ?int $id = null;
    private string $full_name;
    private string $email;
    private string $password;
    private string $role;
    private string $avatar_url;
    private string $created_at;

    public function __construct(array $data = [])
{
    if (!empty($data)) {
        $this->id = $data['id'] ?? null;
        $this->full_name = $data['full_name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password_hash'] ?? $data['password'] ?? '';
        $this->role = $data['role'] ?? 'user';
        $this->avatar_url = $data['avatar_url'] ?? '/images/default-avatar.png';
        $this->created_at = $data['created_at'] ?? date('Y-m-d H:i:s');
    }
}

public function save(): bool
{
    $db = Database::getInstance();

    if ($this->id === null) {
        $sql = "INSERT INTO users (email, password_hash, role, full_name, avatar_url, created_at) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            $this->email,
            $this->password,
            $this->role,
            $this->full_name,
            $this->avatar_url,
            $this->created_at
        ]);
    } else {
        $sql = "UPDATE users SET email = ?, password_hash = ?, role = ?, full_name = ?, avatar_url = ?, created_at = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            $this->email, 
            $this->password, 
            $this->role, 
            $this->full_name, 
            $this->avatar_url,
            $this->created_at, 
            $this->id
        ]);
    }
}

    
    public static function findByEmail(string $email): ?self
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($row = $stmt->fetch()) {
            return new self($row);
        }

        return null;
    }

    public static function findById(int $id): ?self
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);

        if ($row = $stmt->fetch()) {
            return new self($row);
        }

        return null;
    }

    public function verifyPassword(string $password): bool
    {
        return Security::verifyPassword($password, $this->password);
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getRole(): string
    {
        return $this->role;
    }
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getFullName(): string
    {
        return $this->full_name;
    }

    public function getAvatar(): string
    {
        return $this->avatar_url ;
    }

    // Setters
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setAvatar(string $avatar_url): void
{
    $this->avatar_url = $avatar_url;
}
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function setFullName(string $full_name): void
    {
        $this->full_name = $full_name;
    }

    // Admin methods
    public static function findAll(): array
    {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM users");
        return $stmt->fetchAll();
    }

    public static function count(): int
    {
        $db = Database::getInstance();
        return $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }


    public static function delete(int $id): bool
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // public static function update(int $id, array $data): bool
    // {
    //     $db = Database::getInstance();
    //     $updates = [];
    //     $values = [];

    //     foreach ($data as $key => $value) {
    //         if ($key === 'password') {
    //             $updates[] = "password = ?";
    //             $values[] = Security::hashPassword($value);
    //         } else {
    //             $updates[] = "$key = ?";
    //             $values[] = $value;
    //         }
    //     }

    //     $values[] = $id;
    //     $sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE id = ?";
    //     $stmt = $db->prepare($sql);
    //     return $stmt->execute($values);
    // }

    public static function updateUser(int $id, array $data): bool
    {
        $user = self::findById($id);
        $user->setFullName($data['full_name']);
        $user->setEmail($data['email']);
        // $user->setPassword($data['password']);
        $user->setRole($data['role']);
        return $user->save();
    }

    public static function create(array $data): bool
    {
        $user = new self($data);
        return $user->save();
    }

    public static function getEmailById(int $id): ?string
    {
        $user = self::findById($id);
        return $user->getEmail();
    }

    public static function getRoleById(int $id): ?string
    {
        $user = self::findById($id);
        return $user->getRole();
    }
}

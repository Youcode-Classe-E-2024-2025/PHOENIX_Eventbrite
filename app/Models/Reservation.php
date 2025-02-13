<?php 


namespace App\Models;

use App\Core\Database;
use App\Core\Security;
use PDO;

class Reservation
{
    private int $id;
    private int $event_id;
    private int $user_id;
    private string $status; 
    private DateTime $created_at;

    public function ajouterResevation(int $event_id, int $user_id): bool
    {
    }

    public function cancel(int $reservation_id): bool
    {
      
    }

    public function findByUser(int $user_id): array
    {
      
    }

    public function getAvailableSeats(int $event_id): int
    {
      
    }
}






?>
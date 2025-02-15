<?php 


namespace App\Models;

use App\Core\Database;
use App\Core\Security;
use PDO;
use PDOException;

class Reservation
{
    private int $id;
    private int $event_id;
    private int $user_id;
    private string $status; 
    private $created_at;

  
  
    public static function ajouterReservation(int $event_id, int $user_id, int $quantity, float $total_price): bool
    {
        $requet = "INSERT INTO reservations (user_id, event_id, ticket_type, quantity, total_price, status, created_at, updated_at) 
                   VALUES (:user_id, :event_id, 'Gratuit', :quantity, :total_price, 'Confirmé', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        
        $stmt = Database::getInstance()->prepare($requet);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
        // $stmt->bindParam(':ticket_type', $ticket_type, PDO::PARAM_STR);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':total_price', $total_price, PDO::PARAM_STR);

        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
            return false;
        }
    }
    
    
}





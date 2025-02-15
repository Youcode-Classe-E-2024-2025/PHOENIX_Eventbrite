<?php 

    namespace App\Models;

use App\Core\Database;
use PhpParser\Node\Expr\FuncCall;
use Pdo;
use DateTime;

    Class Notification{

public function SendNotification(){

    $pdo = Database::getInstance();
    $now = new DateTime();
    $later = (clone $now)->modify('+24 hours');

    $stmt = $pdo->prepare("
        SELECT e.id AS event_id, e.title, e.event_date, ep.user_id
        FROM events e
        JOIN event_participants ep ON e.id = ep.event_id
        WHERE e.event_date BETWEEN :now AND :later
    ");
    $stmt->execute([
        'now' => $now->format('Y-m-d H:i:s'),
        'later' => $later->format('Y-m-d H:i:s')
    ]);

    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($events as $event) {
        $message = "L'événement '{$event['title']}' aura lieu bientôt (le " . $event['event_date'] . ").";
      $this->createNotification($event['user_id'], $event['event_id'], $message);
    }
}

function getNotifications($userId) {
    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC");
    $stmt->execute(['user_id' => $userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
} function createNotification($userId, $eventId, $message) {
    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, event_id, message) VALUES (:user_id, :event_id, :message)");
    $stmt->execute(['user_id' => $userId, 'event_id' => $eventId, 'message' => $message]);
}

    }

?>
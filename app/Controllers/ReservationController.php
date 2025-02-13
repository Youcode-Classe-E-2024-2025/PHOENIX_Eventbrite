<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Reservation;
use App\Controllers\EventController;
use App\Models\Event;

class ReservationController extends Controller
{

    public function ajouterReservation($id)
    {
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
         $event = Event::selectEventById(id: $id);
            $this->render('Participant/resevation', ['event'=>$event]);

        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $event_id = $id;

            $user_id = intval($_SESSION['user_id']);
            // $ticket_type = $_POST['ticket_type'];
            $quantity = intval($_POST['quantity']);
            $total_price = floatval($_POST['totalPrice']);
            $userReservation = Reservation::ajouterReservation($event_id, $user_id, $quantity, $total_price);
            if ($userReservation){
                echo "Réservation ajoutée avec succès !";
                $this->render('Participant/payment',['event'  => $event_id , 'user_id' => $user_id , 'quantity' => $quantity , 'total_price' => $total_price]);
            } else {
                echo "Une erreur est survenue lors de l'ajout de la réservation.";
            }
        }
    }
}

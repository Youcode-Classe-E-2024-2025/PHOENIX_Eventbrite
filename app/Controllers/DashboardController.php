<?php 
namespace App\Controllers;
use App\Core\Controller;
use App\Models\Event;

class DashboardController extends Controller{
    private Event $Event ;

    public function __construct(){
        $this->Event; 
    }
    

    public function AffichageEventsPracipant($id_user){
        return $this->Event->SelectEventPraticiper($id_user);
    }



} 



?>
<?php 
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Event;
use PHPUnit\Framework\Constraint\Count;

    class PaticipantController extends Controller{

        public function __construct(){
            parent::__construct();
        }

        public function  findAllEvent(){
            $events = Event::findAllEvent();
        return $events ;
        }
        public function accederEventReserver(){
        
        }
        public function Events() {
            $events = $this->findAllEvent(); 
            $this->render('Participant/events', ['events' => $events]);
        }

        public function getPaginationEvent($page){
            $limit = 4 ;
            $Offset = ($page-1) * $limit;

            $events = Event::getPaginationEvent($limit,$Offset);
            $TOTALEVENT = Count($this->findAllEvent());
            $totalPage = ceil($TOTALEVENT/$limit);
            
            echo json_encode([
                'events' => $events,
                'current_page' => $page,
                'total_pages' => $totalPage
            ]);
            exit;
        }
        public function EventsPagination($page) {
            $events = $this->getPaginationEvent($page); 
            $this->render('Participant/events', ['events' => $events]);
        }
    }

?>
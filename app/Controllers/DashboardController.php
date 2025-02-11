<<<<<<< HEAD
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
=======
<?php

namespace App\Controllers;

use App\Core\Controller;
class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function dashboard()
    {
        $case = $_SESSION['user_role'];
        error_reporting(E_ALL);
    ini_set('display_errors', 1);
        
        switch ($case) {
            case 'Admin':
                $this->render('Admin/index');
                break;
            case 'Organisateur':
                $this->render('Organisateur/index');
                break;
            default:
                var_dump('Entering default case'); // Add this line
                $this->render('Participant/index');
                break;
        }
    }
}
>>>>>>> origin/setup

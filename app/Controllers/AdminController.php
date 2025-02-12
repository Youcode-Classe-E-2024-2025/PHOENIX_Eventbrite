<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Validator;
use App\Models\User;

class AdminController extends Controller
{
    public function UpdateUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'get') {
            return $this->render('Admin/edit-user');
        }else{
            return $this->render('Admin/edit-user', [
                'id' => $_GET['id'],
                'user' => User::findById($_GET['id']),
                'roles' => User::getRoleById($_GET['id']),
                'emails' => User::getEmailById($_GET['id']),
            ]);
        }
    }       
}

<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Validator;
use App\Models\User;

class AdminController extends Controller
{
    public function UpdateUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $id = $_SERVER['REQUEST_URI'];
            $id = explode('/', $id);
            $id = end($id);
            $user = User::findById($id);
            $this->render('Admin/editUsers', ['user' => $user]);
        }
        // elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //     $id = $_SERVER['REQUEST_URI'];
        //     $id = explode('/', $id);
        //     $id = end($id);
        //     $user = User::findById($id);
        //     $validator = new Validator($_POST);
        //     $validator->required('email')->email('email')
        //         ->required('password')->minLength('password', 6);
        //     if ($validator->isValid()) {
        //         $user->setEmail($_POST['email']);
        //         if (isset($_POST['password']) && !empty($_POST['password'])) {
        //             $user->setPassword(password_hash($_POST['password'], PASSWORD_DEFAULT));
        //         }
        //         $user->save();
        //         // Redirect or show success message
        //         header('Location: /admin/users');
        //         exit;
        //     } else {
        //         // Handle validation errors
        //         $this->render('Admin/editUsers', [
        //             'user' => $user,
        //             'errors' => $validator->getErrors()
        //         ]);
        //     } 
        // }
    }       
}

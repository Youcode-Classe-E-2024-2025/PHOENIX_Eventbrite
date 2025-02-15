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
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_SERVER['REQUEST_URI'];
        $id = explode('/', $id);
        $id = end($id);
        $user = User::findById($id);
        $data = [
            'full_name' => $_POST['full_name'],
            'email' => $_POST['email'],
            'role' => $_POST['role']
        ];
        User::updateUser($id, $data);
        $this->redirect('/dashboard');
    }
    }    

    public function DeleteUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_SERVER['REQUEST_URI'];
            $id = explode('/', $id);
            $id = end($id);
            var_dump($id);
            User::delete($id);
            $this->redirect('/dashboard');
        }
    }
}

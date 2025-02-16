<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Core\Validator;
use App\Models\User;
use App\Controllers\DashboardController;

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            if (!Security::validateCsrfToken($_POST['csrf_token'] ?? null)) {
                return $this->json(['error' => 'Invalid CSRF token'], 403);
            }
            $validator = new Validator($_POST);
            $validator->required('email')->email('email')
                ->required('password')->minLength('password', 6);

            if ($validator->isValid()) {
                $user = User::findByEmail($_POST['email']);
                if ($user && $user->verifyPassword($_POST['password'])) {
                    $_SESSION['user_id'] = $user->getId();
                    $_SESSION['user_role'] = $user->getRole();
                    $this->redirect('/dashboard');
                }
                $error = 'Invalid email or password';
            } else {
                $error = $validator->getErrors();
            }
        }

        return $this->render('Auth/login', [
            'error' => $error ?? null,
            'email' => $_POST['email'] ?? ''
        ]);
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            if (!Security::validateCsrfToken($_POST['csrf_token'] ?? null)) {
                return $this->json(['error' => 'Invalid CSRF token'], 403);
            }
            $validator = new Validator($_POST);
            $validator->required('email')->email('email')
                ->required('password')->minLength('password', 6)
                ->required('password_confirm')
                ->matches('password_confirm', 'password')
                ->required('first_name')
                ->required('last_name');

            if ($validator->isValid()) {
                if (!User::findByEmail($_POST['email'])) {
                    $user = new User([
                        'email' => $_POST['email'],
                        'password' => Security::hashPassword($_POST['password']),
                        'role' => $_POST['role'],
                        'full_name' => $_POST['first_name'] . ' ' . $_POST['last_name']
                    ]);

                    if ($user->save()) {
                        $_SESSION['user_id'] = $user->getId();
                        $_SESSION['user_role'] = $user->getRole();

                        $this->redirect('/');
                    }

                    $error = 'Error creating account';
                } else {
                    $error = 'Email already exists';
                }
            } else {
                $error = $validator->getErrors();
            }
        }

        return $this->render('Auth/register', [
            'error' => $error ?? null,
            'email' => $_POST['email'] ?? ''
        ]);
    }

    public function logout()
    {
        
        $_SESSION = [];

        
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        
        session_destroy();

        
        $this->redirect('/');
    }


    public function profile()
    {   
    
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $userId = $_SESSION['user_id'] ?? null;
        
            if (!$userId) {
                $this->redirect('/');
            }
    
        
            $user = User::findById($userId);
            
            
            return $this->render('Layouts/profile', [
                'user' => $user,
                'notifications' => [], 
                'events' => []  
            ]);
        }elseif($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = new Validator($_POST);
            $validator->required('full_name')->minLength('full_name', 3)
                ->required('email')->email('email');
            
            // Only validate password if it's provided
            if (!empty($_POST['password'])) {
                $validator->minLength('password', 6);
            }
    
            if ($validator->isValid()) {                
                $user = User::findById($_SESSION['user_id']);
                $user->setFullName($_POST['full_name']);
                $user->setEmail($_POST['email']);
                
                // Handle file upload
                if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = 'uploads/avatars/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    $fileName = uniqid() . '_' . basename($_FILES['avatar']['name']);
                    $targetPath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath)) {
                        $user->setAvatar($targetPath);
                    }
                }
                
                // Only update password if provided
                if (!empty($_POST['password'])) {
                    $user->setPassword(Security::hashPassword($_POST['password']));
                }
                
                $user->save();
                $this->redirect('/profile');
            }
        }
    }
}

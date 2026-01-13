<?php 

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use Database;

class AuthController extends Controller {

    private $userModel;

    public function __construct() {
        parent::__construct();
        // Since Database is manually required in index.php and has no namespace
        $db = Database::getInstance()->getConnection();
        $this->userModel = new User($db);
    }
    
    public function loginForm() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/');
        }
        $this->render('auth/login.twig');
    }

    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->render('auth/login.twig', ['error' => 'Please fill in all fields']);
            return;
        }

        $user = $this->userModel->findByEmail($email);

        if ($user && $this->userModel->verifyPassword($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $this->redirect('/');
        } else {
            $this->render('auth/login.twig', ['error' => 'Invalid credentials']);
        }
    }

    public function registerForm() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/');
        }
        $this->render('auth/register.twig');
    }

    public function register() {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            $this->render('auth/register.twig', ['error' => 'Please fill in all fields']);
            return;
        }

        if ($this->userModel->emailExists($email)) {
             $this->render('auth/register.twig', ['error' => 'Email already registered']);
             return;
        }

        $this->userModel->create([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);

        $this->redirect('/login');
    }

    public function logout() {
        session_destroy();
        $this->redirect('/login');
    }

    public function testTwig() {
        $this->render('test.twig', ['name' => 'Tester']);
    }
}

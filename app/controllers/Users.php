<?php

use App\Lib\Controller;
use App\Models\CSRF;

class Users extends Controller
{
    public function __construct() {}

    public function register(): void
    {
        if (isset($_SESSION['user'])) {
            redirect("");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');
            // Get the JSON input
            $data = json_decode(file_get_contents('php://input'), true);

            $username = validateInput($data['username']) ?? '';
            $email =  validateInput($data['email']) ?? '';
            $password =  validateInput($data['password'])  ?? '';
            $role = (int) validateInput($data['role'])  ?? '';

            if ($role !== 1 && $role !== 2) {
                echo json_encode(['error' => 'Invalid role']);
                exit();
            }

            if (empty($username) || empty($email) || empty($password)) {
                echo json_encode(['error' =>  'All fields are required.']);
                exit;
            }
            $data = [
                "username" => $username,
                "email" => $email,
                "password" => $password,
                "role_id" => $role,
            ];

            try {
                $user = $this->modal("User");
                if ($user->findUserByEmail($email)) {
                    echo json_encode(["error" => "Username or email already exist!"]);
                    exit();
                } else {
                    if ($user->register($data)) {
                        echo json_encode(["success" => "You are successfully registered"]);
                    }
                }
            } catch (\Throwable $th) {
                echo json_encode(["error" => $th->getMessage()]);
            }
        } else {
            $this->view("/auth/signup");
        }
    }
    public function login()
    {
        if (isset($_SESSION['user'])) {
            redirect("");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header("Content-Type: application/json");

            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $data = json_decode(file_get_contents("php://input"), true);

                $email = validateInput($data['email'])  ?? '';
                $password =  validateInput($data['password']) ?? '';

                if (empty($email) || empty($password)) {
                    echo json_encode(['error' =>  'All fields are required.']);
                    exit;
                }
                try {
                    $data = [
                        "email" => $email,
                        "password" => $password,
                    ];
                    $user = $this->modal("User");
                    if ($user->login($data)) {
                        CSRF::generateToken();
                        echo json_encode(['success' => 'Login successful.']);
                        exit();
                    } else {
                        echo json_encode(['error' => 'Invalid email or password.']);
                        exit();
                    }
                } catch (\Throwable $th) {
                    echo json_encode(["error" => $th->getMessage()]);
                }
            }
        } else {
            $this->view('/auth/login');
        }
    }
    public function signout()
    {
        session_destroy();
        redirect("");
    }
    public function activeAccount(){
        $this->view("activeAccount");
    }
    public function forbidden(){
        $this->view("forbidden");
    }
}

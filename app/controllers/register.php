<?php
class Register extends Controller
{
    public function index()
    {
        $this->view('register/index');
    }

    public function create()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $userModel = $this->model('User');

        // Check if username already exists
        if ($userModel->user_exists($username)) {
            echo "Username already taken.";
            return;
        }

        // Create user
        if ($userModel->create_user($username, $password)) {
            echo "Registration successful. You can now log in.";
            header('Location: /login');
        } else {
            echo "Error during registration.";
        }
    }
}
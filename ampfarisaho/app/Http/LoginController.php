<?php
class LoginController
{
    public $error = '';

    public function handle()
    {
        // Only start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require_once __DIR__ . '/../../includes/functions.php';
        require_once __DIR__ . '/../../includes/auth.php';

        $parents = readJSON(__DIR__ . '/../../data/parents.json');
        $headmaster = readJSON(__DIR__ . '/../../data/headmaster.json');

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            // Headmaster login
            if (isset($headmaster['username'], $headmaster['password']) &&
                $username === $headmaster['username'] && $password === $headmaster['password']) {
                $_SESSION['headmaster'] = $username;
                header("Location: index.php?page=headmaster_dashboard");
                exit;
            }

            // Parent login
            foreach ($parents as $p) {
                if (isset($p['username'], $p['password']) &&
                    $p['username'] === $username && $p['password'] === $password) {
                    $_SESSION['parent'] = $username;
                    header("Location: index.php?page=parent_dashboard");
                    exit;
                }
            }

            $this->error = "Invalid username or password.";
        }
    }
}




<?php
class AuthController extends BaseController {
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new UserModel();
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = sanitizeInput($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($username) || empty($password)) {
                $this->setFlashMessage('error', 'Please enter both username and password');
                $this->render('auth/login');
                return;
            }
            
            $user = $this->userModel->authenticate($username, $password);
            
            if ($user) {
                $_SESSION['user'] = $user;
                $this->setFlashMessage('success', 'Welcome back, ' . $user['name'] . '!');
                
                // Redirect based on role
                switch ($user['role']) {
                    case 'headmaster':
                        redirect('/admin/dashboard');
                        break;
                    case 'parent':
                        redirect('/parent/portal');
                        break;
                    default:
                        redirect('/');
                }
            } else {
                $this->setFlashMessage('error', 'Invalid username or password');
                $this->render('auth/login');
            }
        } else {
            $this->render('auth/login', [
                'pageTitle' => 'Login - Tiny Tots Creche'
            ]);
        }
    }
    
    public function logout() {
        session_destroy();
        $this->setFlashMessage('success', 'You have been logged out successfully');
        redirect('/');
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userData = [
                'username' => sanitizeInput($_POST['username'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? '',
                'name' => sanitizeInput($_POST['name'] ?? ''),
                'email' => sanitizeInput($_POST['email'] ?? ''),
                'role' => sanitizeInput($_POST['role'] ?? 'parent')
            ];
            
            // Validation
            $errors = [];
            
            if (empty($userData['username'])) {
                $errors['username'] = 'Username is required';
            }
            
            if (empty($userData['password'])) {
                $errors['password'] = 'Password is required';
            } elseif (strlen($userData['password']) < 6) {
                $errors['password'] = 'Password must be at least 6 characters';
            }
            
            if ($userData['password'] !== $userData['confirm_password']) {
                $errors['confirm_password'] = 'Passwords do not match';
            }
            
            if (empty($userData['name'])) {
                $errors['name'] = 'Full name is required';
            }
            
            $emailError = $this->validateEmail($userData['email']);
            if ($emailError) {
                $errors['email'] = $emailError;
            }
            
            if (!empty($errors)) {
                $this->render('auth/register', [
                    'pageTitle' => 'Register - Tiny Tots Creche',
                    'errors' => $errors,
                    'old' => $userData
                ]);
                return;
            }
            
            try {
                unset($userData['confirm_password']);
                $user = $this->userModel->createUser($userData);
                $this->setFlashMessage('success', 'Registration successful! Please login.');
                redirect('/login');
            } catch (Exception $e) {
                $this->setFlashMessage('error', $e->getMessage());
                $this->render('auth/register', [
                    'pageTitle' => 'Register - Tiny Tots Creche',
                    'old' => $userData
                ]);
            }
        } else {
            $this->render('auth/register', [
                'pageTitle' => 'Register - Tiny Tots Creche'
            ]);
        }
    }
    
    public function profile() {
        requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user']['id'];
            $updateData = [
                'name' => sanitizeInput($_POST['name'] ?? ''),
                'email' => sanitizeInput($_POST['email'] ?? '')
            ];
            
            // Validation
            $errors = [];
            
            if (empty($updateData['name'])) {
                $errors['name'] = 'Full name is required';
            }
            
            $emailError = $this->validateEmail($updateData['email']);
            if ($emailError) {
                $errors['email'] = $emailError;
            }
            
            if (!empty($errors)) {
                $this->render('auth/profile', [
                    'pageTitle' => 'My Profile - Tiny Tots Creche',
                    'errors' => $errors,
                    'old' => array_merge($_SESSION['user'], $updateData)
                ]);
                return;
            }
            
            try {
                $this->userModel->updateUser($userId, $updateData);
                
                // Update session data
                $_SESSION['user'] = array_merge($_SESSION['user'], $updateData);
                
                $this->setFlashMessage('success', 'Profile updated successfully!');
                redirect('/profile');
            } catch (Exception $e) {
                $this->setFlashMessage('error', $e->getMessage());
                $this->render('auth/profile', [
                    'pageTitle' => 'My Profile - Tiny Tots Creche',
                    'old' => array_merge($_SESSION['user'], $updateData)
                ]);
            }
        } else {
            $this->render('auth/profile', [
                'pageTitle' => 'My Profile - Tiny Tots Creche',
                'old' => $_SESSION['user']
            ]);
        }
    }
}
?>

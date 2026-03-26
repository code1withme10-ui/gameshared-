<?php
class AuthController extends BaseController {
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new UserModel();
    }
    
    public function login() {
        // Check if user is already logged in
        if (isset($_SESSION['user'])) {
            $this->redirectBasedOnRole($_SESSION['user']['role']);
            return;
        }
        
        // Handle redirect parameter from registration
        if (isset($_GET['redirect'])) {
            $_SESSION['redirect_after_login'] = sanitizeInput($_GET['redirect']);
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = sanitizeInput($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $remember = isset($_POST['remember']);
            
            // Basic validation
            if (empty($username) || empty($password)) {
                $this->setFlashMessage('error', 'Please enter both username and password');
                $this->render('auth/login', [
                    'pageTitle' => 'Login - Tiny Tots Creche',
                    'error' => 'Please enter both username and password',
                    'old' => ['username' => $username]
                ]);
                return;
            }
            
            // Password complexity check
            if (strlen($password) < 6) {
                $this->setFlashMessage('error', 'Password must be at least 6 characters long');
                $this->render('auth/login', [
                    'pageTitle' => 'Login - Tiny Tots Creche',
                    'error' => 'Password must be at least 6 characters long',
                    'old' => ['username' => $username]
                ]);
                return;
            }
            
            // Check if user account is locked
            $userRecord = $this->userModel->getUserByUsername($username);
            
            if ($userRecord && !empty($userRecord['is_locked'])) {
                $this->setFlashMessage('error', 'Your profile is locked due to multiple failed login attempts. Please use Forgot Password to unlock your account.');
                $this->render('auth/login', [
                    'pageTitle' => 'Login - Tiny Tots Creche',
                    'error' => 'Your profile is locked due to multiple failed login attempts. Please use Forgot Password to unlock your account.',
                    'old' => ['username' => $username]
                ]);
                return;
            }
            
            // Check rate limiting
            if ($this->isRateLimited($username)) {
                if ($userRecord) {
                    $this->userModel->setLockedStatus($username, true);
                    $this->setFlashMessage('error', 'Your profile is locked due to multiple failed login attempts. Please use Forgot Password to unlock your account.');
                    $this->render('auth/login', [
                        'pageTitle' => 'Login - Tiny Tots Creche',
                        'error' => 'Your profile is locked due to multiple failed login attempts. Please use Forgot Password to unlock your account.',
                        'old' => ['username' => $username]
                    ]);
                    return;
                } else {
                    $this->setFlashMessage('error', 'Too many login attempts. Please try again later.');
                    $this->render('auth/login', [
                        'pageTitle' => 'Login - Tiny Tots Creche',
                        'error' => 'Too many login attempts. Please try again later.',
                        'old' => ['username' => $username]
                    ]);
                    return;
                }
            }
            
            // Authenticate user
            $user = $this->userModel->authenticate($username, $password);
            
            // If not found in users.json, check headmaster.json
            if (!$user) {
                $user = $this->authenticateHeadmaster($username, $password);
            }
            
            if ($user) {
                // Log successful login
                $this->logLoginAttempt($username, true, $user['role']);
                
                // Create secure session
                $_SESSION['user'] = $user;
                $_SESSION['login_time'] = time();
                $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
                $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                
                // Set remember me cookie if requested
                if ($remember) {
                    $this->setRememberMeCookie($user['id']);
                }
                
                // Clear rate limiting on successful login
                $this->clearRateLimit($username);
                
                $this->setFlashMessage('success', 'Welcome back, ' . $user['name'] . '!');
                
                // Redirect based on role or stored redirect
                $redirectUrl = $_SESSION['redirect_after_login'] ?? null;
                
                if ($redirectUrl) {
                    // Clear the stored redirect
                    unset($_SESSION['redirect_after_login']);
                    redirect($redirectUrl);
                } else {
                    $this->redirectBasedOnRole($user['role']);
                }
            } else {
                // Log failed login attempt
                $this->logLoginAttempt($username, false);
                
                // Increment rate limiting
                $this->incrementRateLimit($username);
                
                $this->setFlashMessage('error', 'Invalid username or password');
                $this->render('auth/login', [
                    'pageTitle' => 'Login - Tiny Tots Creche',
                    'error' => 'Invalid username or password',
                    'old' => ['username' => $username]
                ]);
            }
        } else {
            $this->render('auth/login', [
                'pageTitle' => 'Login - Tiny Tots Creche'
            ]);
        }
    }
    
    public function logout() {
        // Log logout activity
        if (isset($_SESSION['user'])) {
            $this->logLoginAttempt($_SESSION['user']['username'], false, $_SESSION['user']['role'], 'logout');
        }
        
        // Destroy session
        session_destroy();
        
        // Clear remember me cookie
        if (isset($_COOKIE['remember_me'])) {
            setcookie('remember_me', '', time() - 3600, '/');
        }
        
        $this->setFlashMessage('success', 'You have been logged out successfully');
        redirect('/login');
    }
    
    public function register() {
        // Debug: Check if register method is being called
        error_log("AuthController::register() called with method: " . $_SERVER['REQUEST_METHOD']);
        
        // Check if user is already logged in
        if (isset($_SESSION['user'])) {
            $this->redirectBasedOnRole($_SESSION['user']['role']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Debug: Log POST data
            error_log("POST data received: " . print_r($_POST, true));
            
            $userData = [
                'name' => sanitizeInput($_POST['name'] ?? ''),
                'surname' => sanitizeInput($_POST['surname'] ?? ''),
                'email' => sanitizeInput($_POST['email'] ?? ''),
                'phone' => sanitizeInput($_POST['phone'] ?? ''),
                'address' => sanitizeInput($_POST['address'] ?? ''),
                'city' => sanitizeInput($_POST['city'] ?? ''),
                'province' => sanitizeInput($_POST['province'] ?? ''),
                'postal_code' => sanitizeInput($_POST['postal_code'] ?? ''),
                'id_number' => sanitizeInput($_POST['id_number'] ?? ''),
                'relationship' => sanitizeInput($_POST['relationship'] ?? ''),
                'password' => $_POST['password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? ''
            ];
            
            // Enhanced validation
            $errors = $this->validateRegistration($userData);
            
            // Check if terms and conditions are accepted
            if (!isset($_POST['terms']) || $_POST['terms'] !== 'on') {
                $errors['terms'] = 'You must accept the terms and conditions to create an account';
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
                // Auto-generate username from parent details
                $userData['username'] = $this->generateUsernameFromName($userData['name'], $userData['surname']);
                
                // Auto-set role as parent since account type field is removed
                $userData['role'] = 'parent';
                
                // Hash password securely
                $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
                unset($userData['confirm_password']);
                
                // Debug: Log the data being sent to UserModel
                error_log("Registration data: " . print_r($userData, true));
                
                $user = $this->userModel->createUser($userData);
                
                // Auto-login the user after successful registration
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user'] = $user;
                $_SESSION['login_time'] = time();
                
                // Store username for display in success message
                $_SESSION['generated_username'] = $userData['username'];
                
                $this->setFlashMessage('success', "Registration successful! Your username is: <strong>{$userData['username']}</strong>. You have been automatically logged in.");
                
                // Redirect to intended destination or dashboard
                $redirectUrl = $_SESSION['redirect_after_login'] ?? null;
                
                if ($redirectUrl) {
                    unset($_SESSION['redirect_after_login']);
                    redirect($redirectUrl);
                } else {
                    $this->redirectBasedOnRole($user['role']);
                }
            } catch (Exception $e) {
                $this->setFlashMessage('error', $e->getMessage());
                $this->render('auth/register', [
                    'pageTitle' => 'Register - Tiny Tots Creche',
                    'errors' => ['general' => $e->getMessage()],
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
        
        // Check session timeout
        if ($this->isSessionExpired()) {
            $this->setFlashMessage('error', 'Session expired. Please login again.');
            redirect('/login');
        }
        
        // Update last activity time
        $_SESSION['last_activity'] = time();
        
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
                    'errors' => ['general' => $e->getMessage()],
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
    
    // Private helper methods
    private function redirectBasedOnRole($role) {
        switch ($role) {
            case 'headmaster':
                redirect('/admin/dashboard');
                break;
            case 'parent':
                redirect('/parent/portal');
                break;
            default:
                redirect('/');
        }
    }
    
    // Enhanced validation for comprehensive parent registration
    private function validateRegistration($userData) {
        $errors = [];
        
        if (empty($userData['password'])) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($userData['password']) < 6) {
            $errors['password'] = 'Password must be at least 6 characters';
        } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $userData['password'])) {
            $errors['password'] = 'Password must contain at least one uppercase letter, one lowercase letter, and one number';
        }
        
        if ($userData['password'] !== $userData['confirm_password']) {
            $errors['confirm_password'] = 'Passwords do not match';
        }
        
        // Personal Details Validation
        if (empty($userData['name'])) {
            $errors['name'] = 'Full name is required';
        } elseif (strlen($userData['name']) < 2) {
            $errors['name'] = 'Name must be at least 2 characters';
        }
        
        if (empty($userData['surname'])) {
            $errors['surname'] = 'Surname is required';
        } elseif (strlen($userData['surname']) < 2) {
            $errors['surname'] = 'Surname must be at least 2 characters';
        }
        
        if (empty($userData['email'])) {
            $errors['email'] = 'Email address is required';
        } elseif (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email address';
        }
        
        if (empty($userData['phone'])) {
            $errors['phone'] = 'Phone number is required';
        } elseif (!preg_match('/^(\+27|0)[0-9]{9,15}$/', $userData['phone'])) {
            $errors['phone'] = 'Invalid South African phone number format';
        }
        
        if (empty($userData['address'])) {
            $errors['address'] = 'Physical address is required';
        } elseif (strlen($userData['address']) < 10) {
            $errors['address'] = 'Address must be at least 10 characters';
        }
        
        if (empty($userData['city'])) {
            $errors['city'] = 'City/Town is required';
        } elseif (strlen($userData['city']) < 2) {
            $errors['city'] = 'City/Town must be at least 2 characters';
        }
        
        if (empty($userData['province'])) {
            $errors['province'] = 'Province is required';
        } elseif (!in_array($userData['province'], [
            'gauteng', 'western-cape', 'eastern-cape', 'northern-cape', 
            'free-state', 'kwazulu-natal', 'mpumalanga', 'limpopo', 
            'north-west', 'northern-cape'
        ])) {
            $errors['province'] = 'Invalid province selected';
        }
        
        if (empty($userData['postal_code'])) {
            $errors['postal_code'] = 'Postal code is required';
        } elseif (!preg_match('/^[0-9]{4}$/', $userData['postal_code'])) {
            $errors['postal_code'] = 'Invalid postal code format (4 digits)';
        }
        
        if (empty($userData['id_number'])) {
            $errors['id_number'] = 'ID/Passport number is required';
        } elseif (strlen($userData['id_number']) < 6) {
            $errors['id_number'] = 'ID/Passport number must be at least 6 characters';
        }
        
        if (empty($userData['relationship'])) {
            $errors['relationship'] = 'Relationship to child is required';
        } elseif (!in_array($userData['relationship'], [
            'mother', 'father', 'guardian', 'grandparent', 'aunt', 'uncle', 'sibling', 'other'
        ])) {
            $errors['relationship'] = 'Invalid relationship selected';
        }
        
        return $errors;
    }
    
    private function isRateLimited($username) {
        $rateLimitFile = DATA_PATH . '/rate_limits.json';
        $rateLimits = [];
        
        if (file_exists($rateLimitFile)) {
            $rateLimits = json_decode(file_get_contents($rateLimitFile), true);
        }
        
        $userKey = md5($username . $_SERVER['REMOTE_ADDR']);
        $now = time();
        
        if (isset($rateLimits[$userKey])) {
            $attempts = $rateLimits[$userKey]['attempts'];
            $lastAttempt = $rateLimits[$userKey]['last_attempt'];
            
            // Lock out if 3 attempts
            if ($attempts >= 3) {
                return true;
            }
        }
        
        return false;
    }
    
    private function incrementRateLimit($username) {
        $rateLimitFile = DATA_PATH . '/rate_limits.json';
        $rateLimits = [];
        
        if (file_exists($rateLimitFile)) {
            $rateLimits = json_decode(file_get_contents($rateLimitFile), true);
        }
        
        $userKey = md5($username . $_SERVER['REMOTE_ADDR']);
        $now = time();
        
        if (!isset($rateLimits[$userKey])) {
            $rateLimits[$userKey] = ['attempts' => 0, 'last_attempt' => $now];
        }
        
        $rateLimits[$userKey]['attempts']++;
        $rateLimits[$userKey]['last_attempt'] = $now;
        
        file_put_contents($rateLimitFile, json_encode($rateLimits));
    }
    
    private function clearRateLimit($username) {
        $rateLimitFile = DATA_PATH . '/rate_limits.json';
        $rateLimits = [];
        
        if (file_exists($rateLimitFile)) {
            $rateLimits = json_decode(file_get_contents($rateLimitFile), true);
        }
        
        $userKey = md5($username . $_SERVER['REMOTE_ADDR']);
        unset($rateLimits[$userKey]);
        
        file_put_contents($rateLimitFile, json_encode($rateLimits));
    }
    
    private function logLoginAttempt($username, $success, $role = '', $action = 'login') {
        $logFile = DATA_PATH . '/login_audit.log';
        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $logEntry = sprintf(
            "[%s] %s | Username: %s | IP: %s | Role: %s | Action: %s | User-Agent: %s\n",
            $timestamp,
            $success ? 'SUCCESS' : 'FAILED',
            $username,
            $ip,
            $role,
            $action,
            $userAgent
        );
        
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    private function setRememberMeCookie($userId) {
        $token = bin2hex(random_bytes(32));
        $expiry = time() + (30 * 24 * 60 * 60); // 30 days
        
        // Store token in user data (in production, use database)
        setcookie('remember_me', $token, $expiry, '/', '', true, true);
    }
    
    private function isSessionExpired() {
        $timeout = 30 * 60; // 30 minutes
        $lastActivity = $_SESSION['last_activity'] ?? $_SESSION['login_time'] ?? time();
        
        return (time() - $lastActivity) > $timeout;
    }
    
    private function generateUsernameFromName($name, $surname) {
        // Remove special characters and convert to lowercase
        $cleanName = preg_replace('/[^a-zA-Z\s]/', '', $name);
        $cleanSurname = preg_replace('/[^a-zA-Z\s]/', '', $surname);
        
        // Use first name and surname initial
        $firstName = strtolower($cleanName);
        $surnameInitial = isset($cleanSurname) ? strtolower(substr($cleanSurname, 0, 1)) : '';
        
        $baseUsername = $firstName . $surnameInitial;
        
        // Add random number to ensure uniqueness
        $randomNumber = rand(100, 999);
        $username = $baseUsername . $randomNumber;
        
        // Check if username already exists, if so, generate another
        if ($this->userModel->findByUsername($username)) {
            $username = $firstName . $surnameInitial . rand(1000, 9999);
        }
        
        return $username;
    }
    
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = sanitizeInput($_POST['email'] ?? '');
            
            // Validation
            if (empty($email)) {
                $this->setFlashMessage('error', 'Email address is required');
                $this->render('auth/forgot-password', [
                    'pageTitle' => 'Forgot Password - Tiny Tots Creche',
                    'error' => 'Email address is required',
                    'old' => ['email' => $email]
                ]);
                return;
            }
            
            $emailError = $this->validateEmail($email);
            if ($emailError) {
                $this->setFlashMessage('error', $emailError);
                $this->render('auth/forgot-password', [
                    'pageTitle' => 'Forgot Password - Tiny Tots Creche',
                    'error' => $emailError,
                    'old' => ['email' => $email]
                ]);
                return;
            }
            
            try {
                // Check if email exists in system
                $user = $this->userModel->findByEmail($email);
                
                if (!$user) {
                    $this->setFlashMessage('error', 'Email address not found in our system');
                    $this->render('auth/forgot-password', [
                        'pageTitle' => 'Forgot Password - Tiny Tots Creche',
                        'error' => 'Email address not found in our system',
                        'old' => ['email' => $email]
                    ]);
                    return;
                }
                
                // Generate secure reset token
                $resetToken = bin2hex(random_bytes(32));
                $expiry = time() + (2 * 60 * 60); // 2 hours
                
                // Store reset token (in production, use database)
                $this->storePasswordResetToken($user['id'], $resetToken, $expiry);
                
                // Send reset email (in production, use actual email service)
                $this->sendPasswordResetEmail($email, $resetToken, $user['name']);
                
                $this->setFlashMessage('success', 'Password reset instructions have been sent to your email address');
                $this->render('auth/forgot-password', [
                    'pageTitle' => 'Forgot Password - Tiny Tots Creche',
                    'success' => 'Password reset instructions have been sent to your email address',
                    'email' => $email
                ]);
                
            } catch (Exception $e) {
                $this->setFlashMessage('error', 'An error occurred. Please try again.');
                $this->render('auth/forgot-password', [
                    'pageTitle' => 'Forgot Password - Tiny Tots Creche',
                    'error' => 'An error occurred. Please try again.',
                    'old' => ['email' => $email]
                ]);
            }
        } else {
            $this->render('auth/forgot-password', [
                'pageTitle' => 'Forgot Password - Tiny Tots Creche'
            ]);
        }
    }
    
    public function resetPassword() {
        $token = $_GET['token'] ?? '';
        $email = $_GET['email'] ?? '';
        
        if (empty($token) || empty($email)) {
            $this->setFlashMessage('error', 'Invalid password reset link');
            redirect('/login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Validation
            $errors = [];
            
            if (empty($password)) {
                $errors['password'] = 'Password is required';
            } elseif (strlen($password) < 6) {
                $errors['password'] = 'Password must be at least 6 characters';
            } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $password)) {
                $errors['password'] = 'Password must contain at least one uppercase letter, one lowercase letter, and one number';
            }
            
            if ($password !== $confirmPassword) {
                $errors['confirm_password'] = 'Passwords do not match';
            }
            
            if (!empty($errors)) {
                $this->render('auth/reset-password', [
                    'pageTitle' => 'Reset Password - Tiny Tots Creche',
                    'errors' => $errors,
                    'token' => $token,
                    'email' => $email
                ]);
                return;
            }
            
            try {
                // Verify reset token
                if (!$this->verifyPasswordResetToken($email, $token)) {
                    $this->setFlashMessage('error', 'Invalid or expired reset token');
                    redirect('/login');
                }
                
                // Get user and update password
                $user = $this->userModel->findByEmail($email);
                if ($user) {
                    $this->userModel->updatePassword($user['id'], password_hash($password, PASSWORD_DEFAULT));
                    
                    // Unlock account and clear rate limit
                    $this->userModel->setLockedStatus($user['username'], false);
                    $this->clearRateLimit($user['username']);
                    
                    // Clear reset token
                    $this->clearPasswordResetToken($email, $token);
                    
                    $this->setFlashMessage('success', 'Password has been reset successfully. Please login with your new password.');
                    redirect('/login');
                }
                
            } catch (Exception $e) {
                $this->setFlashMessage('error', 'An error occurred. Please try again.');
                $this->render('auth/reset-password', [
                    'pageTitle' => 'Reset Password - Tiny Tots Creche',
                    'errors' => ['general' => $e->getMessage()],
                    'token' => $token,
                    'email' => $email
                ]);
            }
        } else {
            // Verify token exists before showing form
            if (!$this->verifyPasswordResetToken($email, $token)) {
                $this->setFlashMessage('error', 'Invalid or expired reset token');
                redirect('/login');
            }
            
            $this->render('auth/reset-password', [
                'pageTitle' => 'Reset Password - Tiny Tots Creche',
                'token' => $token,
                'email' => $email
            ]);
        }
    }
    
    private function storePasswordResetToken($userId, $token, $expiry) {
        $resetFile = DATA_PATH . '/password_resets.json';
        $resets = [];
        
        if (file_exists($resetFile)) {
            $resets = json_decode(file_get_contents($resetFile), true);
        }
        
        $resets[] = [
            'user_id' => $userId,
            'email' => $this->userModel->findById($userId)['email'],
            'token' => $token,
            'expiry' => $expiry,
            'created_at' => time()
        ];
        
        file_put_contents($resetFile, json_encode($resets));
    }
    
    private function verifyPasswordResetToken($email, $token) {
        $resetFile = DATA_PATH . '/password_resets.json';
        
        if (!file_exists($resetFile)) {
            return false;
        }
        
        $resets = json_decode(file_get_contents($resetFile), true);
        $now = time();
        
        foreach ($resets as $reset) {
            if ($reset['token'] === $token && $reset['email'] === $email && $reset['expiry'] > $now) {
                return true;
            }
        }
        
        return false;
    }
    
    private function clearPasswordResetToken($email, $token) {
        $resetFile = DATA_PATH . '/password_resets.json';
        
        if (!file_exists($resetFile)) {
            return;
        }
        
        $resets = json_decode(file_get_contents($resetFile), true);
        
        // Remove used token
        $resets = array_filter($resets, function($reset) use ($email, $token) {
            return !($reset['token'] === $token && $reset['email'] === $email);
        });
        
        file_put_contents($resetFile, json_encode(array_values($resets)));
    }
    
    private function sendPasswordResetEmail($email, $token, $name) {
        // In production, this would send an actual email
        // For now, we'll log it for demonstration
        $resetUrl = "http://localhost:8000/reset-password?token=" . $token . "&email=" . urlencode($email);
        
        $emailContent = "
Hello {$name},

You have requested to reset your password for Tiny Tots Creche.

Click the link below to reset your password:
{$resetUrl}

This link will expire in 2 hours.

If you did not request this password reset, please ignore this email.

For security reasons, please do not share this link with anyone.

Best regards,
Tiny Tots Creche Team
        ";
        
        // Log email (in production, use actual email service)
        error_log("Password reset email sent to {$email}: " . $emailContent);
    }
    
    private function authenticateHeadmaster($username, $password) {
        $headmasterFile = DATA_PATH . '/headmaster.json';
        
        if (!file_exists($headmasterFile)) {
            return false;
        }
        
        $headmasters = json_decode(file_get_contents($headmasterFile), true);
        
        foreach ($headmasters as $headmaster) {
            if ($headmaster['username'] === $username && $headmaster['password'] === $password) {
                // Add role and other required fields
                return [
                    'id' => 'headmaster_001',
                    'username' => $headmaster['username'],
                    'password' => $headmaster['password'],
                    'name' => $headmaster['name'],
                    'email' => $headmaster['email'],
                    'phone' => $headmaster['phone'],
                    'role' => 'headmaster'
                ];
            }
        }
        
        return false;
    }
}
?>

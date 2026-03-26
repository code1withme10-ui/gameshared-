<?php
// MVC Base Controller Class
class BaseController {
    protected $flashMessages = [];
    
    public function __construct() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    protected function render($view, $data = []) {
        // Extract data variables
        extract($data);
        
        // Get current request for active navigation
        $request = $_SERVER['REQUEST_URI'] ?? '/';
        $request = parse_url($request, PHP_URL_PATH);
        $request = $request ?: '/';
        
        // Include the view
        $viewFile = VIEWS_PATH . '/' . $view . '.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            // Fallback to error page
            http_response_code(404);
            include VIEWS_PATH . '/errors/404.php';
        }
    }
    
    protected function setFlashMessage($type, $message) {
        $_SESSION['flash_messages'][$type] = $message;
    }
    
    protected function getFlashMessages() {
        $messages = $_SESSION['flash_messages'] ?? [];
        unset($_SESSION['flash_messages']);
        return $messages;
    }
    
    protected function validateCsrf() {
        return isset($_POST['csrf_token']) && 
               hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token']);
    }
    
    protected function generateCsrfToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    protected function validateEmail($email) {
        if (empty($email)) {
            return 'Email is required';
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Invalid email address';
        }
        
        return null;
    }
}
?>

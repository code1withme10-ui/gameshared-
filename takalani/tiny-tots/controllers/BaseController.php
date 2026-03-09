<?php
abstract class BaseController {
    protected $viewData = [];
    
    public function __construct() {
        // Common data for all views
        $this->viewData['user'] = $_SESSION['user'] ?? null;
        $this->viewData['pageTitle'] = 'Tiny Tots Creche';
        $this->viewData['siteName'] = 'Tiny Tots Creche';
    }
    
    protected function render($view, $data = []) {
        $this->viewData = array_merge($this->viewData, $data);
        
        // Extract variables for the view
        extract($this->viewData);
        
        // Include header
        require VIEWS_PATH . '/layouts/header.php';
        
        // Include the main view
        require VIEWS_PATH . '/' . $view . '.php';
        
        // Include footer
        require VIEWS_PATH . '/layouts/footer.php';
    }
    
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function validateCsrf() {
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
    }
    
    protected function generateCsrfToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    protected function setFlashMessage($type, $message) {
        $_SESSION['flash'][$type] = $message;
    }
    
    protected function getFlashMessages() {
        $messages = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return $messages;
    }
    
    protected function validateRequired($data, $requiredFields) {
        $errors = [];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
            }
        }
        return $errors;
    }
    
    protected function validateEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Invalid email address';
        }
        return null;
    }
    
    protected function validatePhone($phone) {
        if (!preg_match('/^[0-9\s\-\+\(\)]{10,}$/', $phone)) {
            return 'Invalid phone number';
        }
        return null;
    }
}
?>

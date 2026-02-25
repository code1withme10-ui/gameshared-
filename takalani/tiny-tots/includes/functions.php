<?php
// Database configuration
define('DB_FILE', __DIR__ . '/../data/users.json');
define('ADMISSIONS_FILE', __DIR__ . '/../data/admissions.json');
define('HEADMASTER_FILE', __DIR__ . '/../data/headmaster.json');

// Include controller classes
require_once __DIR__ . '/HomeController.php';
require_once __DIR__ . '/AdmissionController.php';

// MVC Base Model Class
class BaseModel {
    protected $dataFile;
    
    public function __construct($filename) {
        $this->dataFile = $filename;
        $this->ensureDataFileExists();
    }
    
    protected function readJsonFile() {
        if (!file_exists($this->dataFile)) {
            return [];
        }
        $json = file_get_contents($this->dataFile);
        return json_decode($json, true) ?: [];
    }
    
    protected function writeJsonFile($data) {
        $json = json_encode($data, JSON_PRETTY_PRINT);
        return file_put_contents($this->dataFile, $json) !== false;
    }
    
    protected function ensureDataFileExists() {
        if (!file_exists($this->dataFile)) {
            $this->writeJsonFile([]);
        }
    }
    
    protected function generateId() {
        return uniqid('tt_', true);
    }
    
    protected function validateRequired($data, $requiredFields) {
        $missing = [];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $missing[] = $field;
            }
        }
        return $missing;
    }
    
    protected function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    protected function validatePhone($phone) {
        return preg_match('/^[0-9\s\-\+\(\)]{10,}$/', $phone);
    }
    
    protected function validateIdNumber($idNumber) {
        return preg_match('/^[0-9]{13}$/', $idNumber);
    }
}

// MVC User Model
class UserModel extends BaseModel {
    public function __construct() {
        parent::__construct(DB_FILE);
    }
    
    public function authenticate($username, $password) {
        $users = $this->readJsonFile();
        
        foreach ($users as $user) {
            if ($user['username'] === $username && $user['password'] === $password) {
                return $user;
            }
        }
        
        return false;
    }
    
    public function getUserByUsername($username) {
        $users = $this->readJsonFile();
        
        foreach ($users as $user) {
            if ($user['username'] === $username) {
                return $user;
            }
        }
        
        return false;
    }
    
    public function createUser($userData) {
        $requiredFields = ['username', 'password', 'name', 'email', 'role'];
        $missing = $this->validateRequired($userData, $requiredFields);
        
        if (!empty($missing)) {
            throw new Exception("Missing required fields: " . implode(', ', $missing));
        }
        
        if (!$this->validateEmail($userData['email'])) {
            throw new Exception("Invalid email address");
        }
        
        if ($this->getUserByUsername($userData['username'])) {
            throw new Exception("Username already exists");
        }
        
        $users = $this->readJsonFile();
        
        $newUser = [
            'id' => $this->generateId(),
            'username' => $userData['username'],
            'password' => $userData['password'], // In production, hash this!
            'name' => $userData['name'],
            'email' => $userData['email'],
            'role' => $userData['role'],
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $users[] = $newUser;
        $this->writeJsonFile($users);
        
        return $newUser;
    }
    
    public function getAllUsers() {
        return $this->readJsonFile();
    }
}

// MVC Admission Model
class AdmissionModel extends BaseModel {
    public function __construct() {
        parent::__construct(ADMISSIONS_FILE);
    }
    
    public function createAdmission($admissionData) {
        $requiredFields = [
            'parentFirstName', 'parentSurname', 'contactNumber', 'emailAddress',
            'parentIdNumber', 'residentialAddress', 'childFirstName', 'childSurname',
            'dateOfBirth', 'childGender', 'gradeApplyingFor'
        ];
        
        $missing = $this->validateRequired($admissionData, $requiredFields);
        
        if (!empty($missing)) {
            throw new Exception("Missing required fields: " . implode(', ', $missing));
        }
        
        if (!$this->validateEmail($admissionData['emailAddress'])) {
            throw new Exception("Invalid email address");
        }
        
        if (!$this->validatePhone($admissionData['contactNumber'])) {
            throw new Exception("Invalid phone number");
        }
        
        if (!$this->validateIdNumber($admissionData['parentIdNumber'])) {
            throw new Exception("Invalid parent ID number");
        }
        
        // Validate age and grade
        $age = $this->calculateAge($admissionData['dateOfBirth']);
        $gradeValidation = $this->validateGradeForAge($admissionData['gradeApplyingFor'], $age);
        
        if (!$gradeValidation['valid']) {
            throw new Exception($gradeValidation['message']);
        }
        
        $admissions = $this->readJsonFile();
        
        $newAdmission = [
            'applicationID' => $this->generateApplicationId(),
            'id' => $this->generateId(),
            'parentFirstName' => $admissionData['parentFirstName'],
            'parentSurname' => $admissionData['parentSurname'],
            'contactNumber' => $admissionData['contactNumber'],
            'emailAddress' => $admissionData['emailAddress'],
            'parentIdNumber' => $admissionData['parentIdNumber'],
            'residentialAddress' => $admissionData['residentialAddress'],
            'childFirstName' => $admissionData['childFirstName'],
            'childSurname' => $admissionData['childSurname'],
            'dateOfBirth' => $admissionData['dateOfBirth'],
            'childGender' => $admissionData['childGender'],
            'gradeApplyingFor' => $admissionData['gradeApplyingFor'],
            'emergencyContactName' => $admissionData['emergencyContactName'] ?? '',
            'emergencyContactPhone' => $admissionData['emergencyContactPhone'] ?? '',
            'transportation' => $admissionData['transportation'] ?? 'none',
            'specialNeeds' => $admissionData['specialNeeds'] ?? '',
            'status' => 'Pending',
            'submittedAt' => date('Y-m-d H:i:s'),
            'age' => $age
        ];
        
        // Add optional fields
        $optionalFields = [
            'motherFirstName', 'motherSurname', 'motherIdNumber', 'motherOccupation',
            'fatherFirstName', 'fatherSurname', 'fatherIdNumber', 'fatherOccupation'
        ];
        
        foreach ($optionalFields as $field) {
            if (isset($admissionData[$field])) {
                $newAdmission[$field] = $admissionData[$field];
            }
        }
        
        $admissions[] = $newAdmission;
        $this->writeJsonFile($admissions);
        
        return $newAdmission;
    }
    
    public function getAllAdmissions() {
        return $this->readJsonFile();
    }
    
    public function getAdmissionById($id) {
        $admissions = $this->readJsonFile();
        
        foreach ($admissions as $admission) {
            if ($admission['id'] === $id) {
                return $admission;
            }
        }
        
        return false;
    }
    
    private function generateApplicationId() {
        $year = date('Y');
        $sequence = rand(1000, 9999);
        return "TT{$year}{$sequence}";
    }
    
    private function calculateAge($dateOfBirth) {
        $dob = new DateTime($dateOfBirth);
        $today = new DateTime();
        return $today->diff($dob)->y;
    }
    
    private function validateGradeForAge($grade, $age) {
        $gradeCategories = [
            'grade_r' => ['min_age' => 4, 'max_age' => 5],
            'grade_1' => ['min_age' => 6, 'max_age' => 7],
            'grade_2' => ['min_age' => 7, 'max_age' => 8],
            'grade_3' => ['min_age' => 8, 'max_age' => 9]
        ];
        
        if (!isset($gradeCategories[$grade])) {
            return ['valid' => false, 'message' => 'Invalid grade selection'];
        }
        
        $category = $gradeCategories[$grade];
        
        if ($age < $category['min_age'] || $age > $category['max_age']) {
            return [
                'valid' => false, 
                'message' => "Age {$age} is not suitable for {$grade}. Required age: {$category['min_age']}-{$category['max_age']} years"
            ];
        }
        
        return ['valid' => true, 'message' => 'Age is appropriate for selected grade'];
    }
}

// MVC Base Controller Class
class BaseController {
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
        require 'includes/header.php';
        
        // Include the main view
        require 'views/' . $view . '.php';
        
        // Include footer
        require 'includes/footer.php';
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

// Helper functions (keeping for backward compatibility)
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function readJsonFile($filename) {
    if (!file_exists($filename)) {
        return [];
    }
    $json = file_get_contents($filename);
    return json_decode($json, true) ?: [];
}

function writeJsonFile($filename, $data) {
    $json = json_encode($data, JSON_PRETTY_PRINT);
    return file_put_contents($filename, $json) !== false;
}

function findUser($username, $password) {
    $userModel = new UserModel();
    return $userModel->authenticate($username, $password);
}

function findHeadmaster($username, $password) {
    $headmasters = readJsonFile(HEADMASTER_FILE);
    
    foreach ($headmasters as $headmaster) {
        if ($headmaster['username'] === $username && $headmaster['password'] === $password) {
            return $headmaster;
        }
    }
    
    return false;
}

function saveAdmission($admissionData) {
    $admissionModel = new AdmissionModel();
    return $admissionModel->createAdmission($admissionData);
}

function getAllAdmissions() {
    $admissionModel = new AdmissionModel();
    return $admissionModel->getAllAdmissions();
}

function getAdmission($id) {
    $admissionModel = new AdmissionModel();
    return $admissionModel->getAdmissionById($id);
}

function requireLogin() {
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit();
    }
}

function requireRole($role) {
    requireLogin();
    if ($_SESSION['user']['role'] !== $role) {
        header('Location: unauthorized.php');
        exit();
    }
}

// Grade categories for validation
$gradeCategories = [
    'grade_r' => ['label' => 'Grade R (4-5 years)', 'description' => 'Foundation phase preparation'],
    'grade_1' => ['label' => 'Grade 1 (6-7 years)', 'description' => 'Beginning of formal education'],
    'grade_2' => ['label' => 'Grade 2 (7-8 years)', 'description' => 'Building foundational skills'],
    'grade_3' => ['label' => 'Grade 3 (8-9 years)', 'description' => 'Intermediate phase preparation']
];

// Create data directory if it doesn't exist
if (!file_exists(__DIR__ . '/../data')) {
    mkdir(__DIR__ . '/../data', 0755, true);
}
?>

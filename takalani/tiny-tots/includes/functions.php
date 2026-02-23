<?php
// Database configuration
define('DB_FILE', __DIR__ . '/../data/users.json');
define('ADMISSIONS_FILE', __DIR__ . '/../data/admissions.json');
define('HEADMASTER_FILE', __DIR__ . '/../data/headmaster.json');

// Helper function to read JSON file
function readJsonFile($filename) {
    if (!file_exists($filename)) {
        return [];
    }
    $json = file_get_contents($filename);
    return json_decode($json, true) ?: [];
}

// Helper function to write JSON file
function writeJsonFile($filename, $data) {
    $json = json_encode($data, JSON_PRETTY_PRINT);
    return file_put_contents($filename, $json) !== false;
}

// Find user by username and password
function findUser($username, $password) {
    $users = readJsonFile(DB_FILE);
    
    foreach ($users as $user) {
        if ($user['username'] === $username && $user['password'] === $password) {
            return $user;
        }
    }
    return null;
}

// Find headmaster by username and password
function findHeadmaster($username, $password) {
    $headmasters = readJsonFile(HEADMASTER_FILE);
    
    foreach ($headmasters as $headmaster) {
        if ($headmaster['username'] === $username && $headmaster['password'] === $password) {
            return $headmaster;
        }
    }
    return null;
}

// Save admission application
function saveAdmission($admissionData) {
    $admissions = readJsonFile(ADMISSIONS_FILE);
    
    // Generate unique application ID
    $applicationId = 'APP' . date('Y') . str_pad(count($admissions) + 1, 4, '0', STR_PAD_LEFT);
    $admissionData['applicationID'] = $applicationId;
    $admissionData['submittedAt'] = date('Y-m-d H:i:s');
    $admissionData['status'] = 'Pending';
    
    $admissions[] = $admissionData;
    
    return writeJsonFile(ADMISSIONS_FILE, $admissions);
}

// Get all admissions
function getAllAdmissions() {
    return readJsonFile(ADMISSIONS_FILE);
}

// Update admission status
function updateAdmissionStatus($applicationId, $status) {
    $admissions = readJsonFile(ADMISSIONS_FILE);
    
    foreach ($admissions as &$admission) {
        if ($admission['applicationID'] === $applicationId) {
            $admission['status'] = $status;
            $admission['updatedAt'] = date('Y-m-d H:i:s');
            break;
        }
    }
    
    return writeJsonFile(ADMISSIONS_FILE, $admissions);
}

// Grade categories for admission validation
$gradeCategories = [
    '3_months' => [
        'label' => '3 Months - 1 Year',
        'min_age' => 3,
        'max_age' => 12
    ],
    '1_2_years' => [
        'label' => '1 - 2 Years',
        'min_age' => 12,
        'max_age' => 24
    ],
    '2_3_years' => [
        'label' => '2 - 3 Years',
        'min_age' => 24,
        'max_age' => 36
    ],
    '3_4_years' => [
        'label' => '3 - 4 Years',
        'min_age' => 36,
        'max_age' => 48
    ],
    '4_5_years' => [
        'label' => '4 - 5 Years (Grade R)',
        'min_age' => 48,
        'max_age' => 60
    ],
    '5_6_years' => [
        'label' => '5 - 6 Years (Grade RR)',
        'min_age' => 60,
        'max_age' => 72
    ]
];

// Calculate age from date of birth
function calculateAge($dateOfBirth) {
    $birthDate = new DateTime($dateOfBirth);
    $currentDate = new DateTime();
    $age = $currentDate->diff($birthDate);
    return $age->y * 12 + $age->m; // Age in months
}

// Validate age against grade category
function validateAgeForGrade($dateOfBirth, $gradeCategory) {
    global $gradeCategories;
    
    if (!isset($gradeCategories[$gradeCategory])) {
        return false;
    }
    
    $ageInMonths = calculateAge($dateOfBirth);
    $category = $gradeCategories[$gradeCategory];
    
    return $ageInMonths >= $category['min_age'] && $ageInMonths <= $category['max_age'];
}

// Sanitize input
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user']);
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

// Check user role
function hasRole($role) {
    return isLoggedIn() && $_SESSION['user']['role'] === $role;
}

// Create data directory if it doesn't exist
if (!file_exists(__DIR__ . '/../data')) {
    mkdir(__DIR__ . '/../data', 0755, true);
}
?>

<?php
class AdminController extends BaseController {
    private $admissionModel;
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->admissionModel = new AdmissionModel();
        $this->userModel = new UserModel();
    }
    
    public function dashboard() {
        requireRole('headmaster');
        
        $admissions = $this->admissionModel->getAllAdmissions();
        $users = $this->userModel->getAllUsers();
        
        // Calculate statistics
        $stats = [
            'total_admissions' => count($admissions),
            'pending_admissions' => count($this->admissionModel->getAdmissionsByStatus('Pending')),
            'approved_admissions' => count($this->admissionModel->getAdmissionsByStatus('Approved')),
            'rejected_admissions' => count($this->admissionModel->getAdmissionsByStatus('Rejected')),
            'total_users' => count($users),
            'total_parents' => count(array_filter($users, function($user) {
                return $user['role'] === 'parent';
            })),
            'total_headmasters' => count(array_filter($users, function($user) {
                return $user['role'] === 'headmaster';
            }))
        ];
        
        // Get recent admissions (last 10)
        $recentAdmissions = array_slice(array_reverse($admissions), 0, 10);
        
        // Get admissions by grade
        $admissionsByGrade = [];
        foreach ($admissions as $admission) {
            $grade = $admission['gradeApplyingFor'];
            if (!isset($admissionsByGrade[$grade])) {
                $admissionsByGrade[$grade] = 0;
            }
            $admissionsByGrade[$grade]++;
        }
        
        $this->render('admin/dashboard', [
            'pageTitle' => 'Admin Dashboard - Tiny Tots Creche',
            'stats' => $stats,
            'recentAdmissions' => $recentAdmissions,
            'admissionsByGrade' => $admissionsByGrade,
            'gradeCategories' => [
                'grade_r' => 'Grade R',
                'grade_1' => 'Grade 1',
                'grade_2' => 'Grade 2',
                'grade_3' => 'Grade 3'
            ]
        ]);
    }
    
    public function users() {
        requireRole('headmaster');
        
        $users = $this->userModel->getAllUsers();
        $role = $_GET['role'] ?? 'all';
        
        if ($role !== 'all') {
            $users = array_filter($users, function($user) use ($role) {
                return $user['role'] === $role;
            });
        }
        
        $this->render('admin/users', [
            'pageTitle' => 'Manage Users - Tiny Tots Creche',
            'users' => $users,
            'role' => $role,
            'stats' => [
                'total' => count($this->userModel->getAllUsers()),
                'parents' => count(array_filter($this->userModel->getAllUsers(), function($user) {
                    return $user['role'] === 'parent';
                })),
                'headmasters' => count(array_filter($this->userModel->getAllUsers(), function($user) {
                    return $user['role'] === 'headmaster';
                }))
            ]
        ]);
    }
    
    public function createUser() {
        requireRole('headmaster');
        
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
            
            if (!in_array($userData['role'], ['parent', 'headmaster'])) {
                $errors['role'] = 'Invalid role selected';
            }
            
            if (!empty($errors)) {
                $this->render('admin/create-user', [
                    'pageTitle' => 'Create User - Tiny Tots Creche',
                    'errors' => $errors,
                    'old' => $userData
                ]);
                return;
            }
            
            try {
                unset($userData['confirm_password']);
                $user = $this->userModel->createUser($userData);
                $this->setFlashMessage('success', 'User created successfully!');
                redirect('/admin/users');
            } catch (Exception $e) {
                $this->setFlashMessage('error', $e->getMessage());
                $this->render('admin/create-user', [
                    'pageTitle' => 'Create User - Tiny Tots Creche',
                    'old' => $userData
                ]);
            }
        } else {
            $this->render('admin/create-user', [
                'pageTitle' => 'Create User - Tiny Tots Creche'
            ]);
        }
    }
    
    public function deleteUser() {
        requireRole('headmaster');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
        }
        
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
            $this->json(['error' => 'User ID is required'], 400);
        }
        
        // Prevent deletion of current user
        if ($id === $_SESSION['user']['id']) {
            $this->json(['error' => 'Cannot delete your own account'], 400);
        }
        
        if ($this->userModel->deleteUser($id)) {
            $this->json(['success' => true, 'message' => 'User deleted successfully']);
        } else {
            $this->json(['error' => 'Failed to delete user'], 500);
        }
    }
    
    public function settings() {
        requireRole('headmaster');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $settings = [
                'site_name' => sanitizeInput($_POST['site_name'] ?? 'Tiny Tots Creche'),
                'contact_email' => sanitizeInput($_POST['contact_email'] ?? ''),
                'contact_phone' => sanitizeInput($_POST['contact_phone'] ?? ''),
                'contact_address' => sanitizeInput($_POST['contact_address'] ?? ''),
                'admissions_open' => isset($_POST['admissions_open']),
                'max_grade_r' => intval($_POST['max_grade_r'] ?? 30),
                'max_grade_1' => intval($_POST['max_grade_1'] ?? 30),
                'max_grade_2' => intval($_POST['max_grade_2'] ?? 30),
                'max_grade_3' => intval($_POST['max_grade_3'] ?? 30)
            ];
            
            // Save settings to file
            $settingsFile = DATA_PATH . '/settings.json';
            file_put_contents($settingsFile, json_encode($settings, JSON_PRETTY_PRINT));
            
            $this->setFlashMessage('success', 'Settings updated successfully!');
            redirect('/admin/settings');
        } else {
            // Load current settings
            $settingsFile = DATA_PATH . '/settings.json';
            $settings = file_exists($settingsFile) ? json_decode(file_get_contents($settingsFile), true) : [];
            
            $this->render('admin/settings', [
                'pageTitle' => 'Settings - Tiny Tots Creche',
                'settings' => $settings
            ]);
        }
    }
}
?>

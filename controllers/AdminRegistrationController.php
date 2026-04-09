<?php
class AdminRegistrationController extends BaseController {
    private $userModel;
    private $admissionModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new UserModel();
        $this->admissionModel = new AdmissionModel();
    }
    
    public function registerParent() {
        requireRole('headmaster');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Generate username from email for parents
            $emailParts = explode('@', sanitizeInput($_POST['email']));
            $username = $emailParts[0] . rand(100, 999);
            
            $userData = [
                'username' => $username,
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'name' => sanitizeInput($_POST['name']),
                'surname' => sanitizeInput($_POST['surname']),
                'email' => sanitizeInput($_POST['email']),
                'phone' => sanitizeInput($_POST['phone']),
                'address' => sanitizeInput($_POST['address']),
                'city' => sanitizeInput($_POST['city']),
                'province' => sanitizeInput($_POST['province']),
                'postal_code' => sanitizeInput($_POST['postal_code']),
                'id_number' => sanitizeInput($_POST['id_number']),
                'relationship' => sanitizeInput($_POST['relationship']),
                'role' => 'parent',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            // Validate email
            if (!$this->userModel->validateEmail($userData['email'])) {
                $errors['email'] = 'Please enter a valid email address';
            }
            
            // Validate ID number
            if (!$this->userModel->validateIdNumber($userData['id_number'])) {
                $errors['id_number'] = 'ID number must be 8-13 digits';
            }
            
            if (empty($errors)) {
                $parentId = $this->userModel->createUser($userData);
                
                if ($parentId) {
                    $_SESSION['success'] = "Parent registered successfully! Now you can add applications for this parent.";
                    // Redirect to add application with parent ID (like parent portal)
                    redirect('/admin/add-application?parent_id=' . urlencode($parentId) . '&from_registration=1');
                } else {
                    $_SESSION['error'] = "Failed to register parent. Please try again.";
                }
            }
        }
        
        $this->render('admin/register-parent', [
            'pageTitle' => 'Register Parent - Admin',
            'errors' => $errors ?? []
        ]);
    }
    
    public function addApplication() {
        requireRole('headmaster');
        
        $parentId = $_GET['parent_id'] ?? '';
        $parent = null;
        $fromRegistration = isset($_GET['from_registration']);
        
        error_log("DEBUG: Parent ID from URL: " . $parentId);
        error_log("DEBUG: From registration flag: " . ($fromRegistration ? 'true' : 'false'));
        
        if ($parentId) {
            $parent = $this->userModel->getUserById($parentId);
            error_log("DEBUG: Parent data loaded: " . print_r($parent, true));
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle file uploads
            $documents = [];
            $uploadDir = __DIR__ . '/../public/uploads/documents/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Process uploaded documents
            $documentFields = [
                'childBirthCertificate' => 'childBirthCertificate',
                'parentIdDocument' => 'parentIdDocument', 
                'clinicalReport' => 'clinicalReport',
                'previousSchoolReport' => 'previousSchoolReport'
            ];
            
            foreach ($documentFields as $docType => $fieldName) {
                if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES[$fieldName];
                    $fileName = time() . '_' . basename($file['name']);
                    $targetPath = $uploadDir . $fileName;
                    
                    // Validate file type and size
                    $allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                    $maxSize = 2 * 1024 * 1024; // 2MB
                    
                    if (in_array($file['type'], $allowedTypes) && $file['size'] <= $maxSize) {
                        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                            $documents[$docType] = $fileName;
                        }
                    }
                }
            }
            
            $admissionData = [
                'parent_id' => sanitizeInput($_POST['parent_id']),
                'parentFirstName' => sanitizeInput($_POST['parentFirstName']),
                'parentSurname' => sanitizeInput($_POST['parentSurname']),
                'contactNumber' => sanitizeInput($_POST['contactNumber']),
                'emailAddress' => sanitizeInput($_POST['emailAddress']),
                'parentIdNumber' => sanitizeInput($_POST['parentIdNumber']),
                'residentialAddress' => sanitizeInput($_POST['residentialAddress']),
                'relationshipToChild' => sanitizeInput($_POST['relationshipToChild'] ?? ''),
                'childFirstName' => sanitizeInput($_POST['childFirstName']),
                'childSurname' => sanitizeInput($_POST['childSurname']),
                'childIdNumber' => sanitizeInput($_POST['childIdNumber']),
                'dateOfBirth' => sanitizeInput($_POST['dateOfBirth']),
                'childGender' => sanitizeInput($_POST['childGender']),
                'gradeApplyingFor' => sanitizeInput($_POST['gradeApplyingFor']),
                'emergencyContactName' => sanitizeInput($_POST['emergencyContactName']),
                'emergencyContactPhone' => sanitizeInput($_POST['emergencyContactPhone']),
                'emergencyContactAddress' => sanitizeInput($_POST['emergencyContactAddress']),
                'childAddress' => sanitizeInput($_POST['childAddress'] ?? ''),
                'transportation' => sanitizeInput($_POST['transportation'] ?? ''),
                'specialNeeds' => sanitizeInput($_POST['specialNeeds'] ?? ''),
                'documents' => $documents,
                'status' => 'Pending',
                'submitted_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Calculate age
            $birthDate = new DateTime($admissionData['dateOfBirth']);
            $today = new DateTime();
            $age = $birthDate->diff($today)->y;
            $admissionData['age'] = $age;
            $admissionData['child_name'] = $admissionData['childFirstName'] . ' ' . $admissionData['childSurname'];
            $admissionData['child_age'] = $age;
            $admissionData['grade'] = $admissionData['gradeApplyingFor'];
            
            // Grade validation is now handled client-side with auto-selection
            // No need for server-side validation as grade is automatically set based on age
            
            // Only proceed if no validation errors
            if (empty($errors)) {
                if ($this->admissionModel->createAdmission($admissionData)) {
                    $_SESSION['success'] = "Application added successfully for " . $admissionData['child_name'];
                    // Keep the same parent loaded for next application (like parent portal)
                    redirect('/admin/add-application?parent_id=' . urlencode($parentId) . '&success=1');
                } else {
                    $_SESSION['error'] = "Failed to add application. Please try again.";
                }
            }
        }
        
        $this->render('admin/add-application', [
            'pageTitle' => 'Add Application - Admin',
            'parent' => $parent,
            'fromRegistration' => $fromRegistration ?? false,
            'errors' => $errors ?? [],
            'gradeCategories' => [
                'toddlers' => 'Toddlers (0-2 years)',
                'playgroup' => 'Playgroup (2-3 years)',
                'preschool' => 'Pre-School (3-4 years)',
                'grade_r' => 'Grade R (4-5 years)',
                'grade_1' => 'Grade 1 (5-6 years)',
                'foundation' => 'Foundation Phase (6-7 years)'
            ]
        ]);
    }
    
    public function searchParents() {
        requireRole('headmaster');
        
        $search = $_GET['search'] ?? '';
        $parents = [];
        
        if (!empty($search)) {
            $parents = $this->userModel->searchParents($search);
        }
        
        header('Content-Type: application/json');
        echo json_encode($parents);
        exit;
    }
    
    public function deleteUser() {
        requireRole('headmaster');
        
        error_log("DEBUG: deleteUser method called");
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $userId = sanitizeInput($input['id'] ?? '');
            
            error_log("DEBUG: User ID to delete: " . $userId);
            
            if (empty($userId)) {
                error_log("DEBUG: Empty user ID");
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'User ID is required']);
                exit;
            }
            
            // Prevent deleting the current logged-in user
            if ($userId === $_SESSION['user']['id']) {
                error_log("DEBUG: Attempting to delete own account");
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'You cannot delete your own account']);
                exit;
            }
            
            // Check if user has associated admissions
            $admissions = $this->admissionModel->getApplicationsByParent($userId);
            error_log("DEBUG: Admissions found for user: " . count($admissions));
            
            if (!empty($admissions)) {
                error_log("DEBUG: User has associated applications, cannot delete");
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Cannot delete user with associated applications. Please delete applications first.']);
                exit;
            }
            
            // Delete the user
            error_log("DEBUG: Attempting to delete user from model");
            $deleteResult = $this->userModel->deleteUser($userId);
            error_log("DEBUG: Delete result: " . ($deleteResult ? 'true' : 'false'));
            
            if ($deleteResult) {
                error_log("DEBUG: User deleted successfully");
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
            } else {
                error_log("DEBUG: Failed to delete user");
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Failed to delete user']);
            }
        } else {
            error_log("DEBUG: Invalid request method: " . $_SERVER['REQUEST_METHOD']);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Invalid request method']);
        }
        exit;
    }
    
    public function listParents() {
        requireRole('headmaster');
        
        // Get all parents
        $allUsers = $this->userModel->readJsonFile();
        $parents = [];
        
        foreach ($allUsers as $user) {
            if ($user['role'] === 'parent') {
                // Get applications for this parent
                $parentApplications = $this->admissionModel->getApplicationsByParent($user['id']);
                
                // Count documents
                $documentCount = 0;
                $documents = [];
                
                foreach ($parentApplications as $app) {
                    if (!empty($app['childBirthCertificate'])) {
                        $documentCount++;
                        $documents[] = ['type' => 'Birth Certificate', 'path' => $app['childBirthCertificate'], 'app_id' => $app['applicationID']];
                    }
                    if (!empty($app['parentIdDocument'])) {
                        $documentCount++;
                        $documents[] = ['type' => 'Parent ID', 'path' => $app['parentIdDocument'], 'app_id' => $app['applicationID']];
                    }
                    if (!empty($app['clinicalReport'])) {
                        $documentCount++;
                        $documents[] = ['type' => 'Clinical Report', 'path' => $app['clinicalReport'], 'app_id' => $app['applicationID']];
                    }
                    if (!empty($app['previousSchoolReport'])) {
                        $documentCount++;
                        $documents[] = ['type' => 'School Report', 'path' => $app['previousSchoolReport'], 'app_id' => $app['applicationID']];
                    }
                }
                
                $parents[] = [
                    'id' => $user['id'],
                    'firstName' => $user['firstName'] ?? $user['name'] ?? 'Unknown',
                    'surname' => $user['surname'] ?? $user['lastName'] ?? $user['surname'] ?? 'Unknown',
                    'email' => $user['email'] ?? '',
                    'phone' => $user['phone'] ?? '',
                    'idNumber' => $user['idNumber'] ?? '',
                    'address' => $user['address'] ?? '',
                    'application_count' => count($parentApplications),
                    'document_count' => $documentCount,
                    'documents' => $documents,
                    'applications' => $parentApplications
                ];
            }
        }
        
        $this->render('admin/parents-list', [
            'pageTitle' => 'Parents List - Admin',
            'parents' => $parents
        ]);
    }
    
    public function viewParentDocuments() {
        requireRole('headmaster');
        
        $parentId = $_GET['parent_id'] ?? '';
        if (empty($parentId)) {
            $_SESSION['error'] = 'Parent ID is required';
            redirect('/admin/parents-list');
            return;
        }
        
        // Get parent information
        $parent = $this->userModel->getUserById($parentId);
        if (!$parent || $parent['role'] !== 'parent') {
            $_SESSION['error'] = 'Parent not found';
            redirect('/admin/parents-list');
            return;
        }
        
        // Get all applications for this parent
        $parentApplications = $this->admissionModel->getApplicationsByParent($parentId);
        
        // Collect all documents
        $allDocuments = [];
        foreach ($parentApplications as $app) {
            if (!empty($app['childBirthCertificate'])) {
                $allDocuments[] = [
                    'type' => 'Birth Certificate',
                    'filename' => basename($app['childBirthCertificate']),
                    'path' => $app['childBirthCertificate'],
                    'child_name' => $app['child_name'] ?? $app['childFirstName'] . ' ' . $app['childSurname'],
                    'application_id' => $app['applicationID'],
                    'submitted_at' => $app['submitted_at'] ?? $app['submittedAt'] ?? 'now'
                ];
            }
            if (!empty($app['parentIdDocument'])) {
                $allDocuments[] = [
                    'type' => 'Parent ID Document',
                    'filename' => basename($app['parentIdDocument']),
                    'path' => $app['parentIdDocument'],
                    'child_name' => $app['child_name'] ?? $app['childFirstName'] . ' ' . $app['childSurname'],
                    'application_id' => $app['applicationID'],
                    'submitted_at' => $app['submitted_at'] ?? $app['submittedAt'] ?? 'now'
                ];
            }
            if (!empty($app['clinicalReport'])) {
                $allDocuments[] = [
                    'type' => 'Clinical Report',
                    'filename' => basename($app['clinicalReport']),
                    'path' => $app['clinicalReport'],
                    'child_name' => $app['child_name'] ?? $app['childFirstName'] . ' ' . $app['childSurname'],
                    'application_id' => $app['applicationID'],
                    'submitted_at' => $app['submitted_at'] ?? $app['submittedAt'] ?? 'now'
                ];
            }
            if (!empty($app['previousSchoolReport'])) {
                $allDocuments[] = [
                    'type' => 'School Report',
                    'filename' => basename($app['previousSchoolReport']),
                    'path' => $app['previousSchoolReport'],
                    'child_name' => $app['child_name'] ?? $app['childFirstName'] . ' ' . $app['childSurname'],
                    'application_id' => $app['applicationID'],
                    'submitted_at' => $app['submitted_at'] ?? $app['submittedAt'] ?? 'now'
                ];
            }
        }
        
        // Sort documents by submission date (newest first)
        usort($allDocuments, function($a, $b) {
            return strtotime($b['submitted_at']) - strtotime($a['submitted_at']);
        });
        
        $this->render('admin/parent-documents', [
            'pageTitle' => 'Documents - ' . ($parent['firstName'] ?? $parent['name'] ?? 'Unknown') . ' ' . ($parent['surname'] ?? $parent['lastName'] ?? 'Unknown'),
            'parent' => $parent,
            'documents' => $allDocuments,
            'applications' => $parentApplications
        ]);
    }
}
?>

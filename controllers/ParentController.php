<?php
// Parent Controller
class ParentController extends BaseController {
    
    public function __construct() {
        parent::__construct();
        requireLogin();
        requireRole('parent');
    }
    
    public function portal() {
        // Get parent's admission applications
        $admissionModel = new AdmissionModel();
        $parentApplications = $admissionModel->getApplicationsByParent($_SESSION['user']['id']);
        
        // Debug: Log the parent ID and applications found
        error_log("Parent ID: " . $_SESSION['user']['id']);
        error_log("Applications found: " . count($parentApplications));
        
        $this->render('parent/portal', [
            'pageTitle' => 'Parent Portal - Tiny Tots Creche',
            'applications' => $parentApplications
        ]);
    }
    
    public function profile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->updateProfile();
        } else {
            $this->render('parent/profile', [
                'pageTitle' => 'My Profile - Tiny Tots Creche',
                'user' => $_SESSION['user']
            ]);
        }
    }
    
    public function applicationStatus() {
        $applicationId = $_GET['id'] ?? '';
        $admissionModel = new AdmissionModel('admissions.json');
        $application = $admissionModel->getApplicationById($applicationId);
        
        if (!$application || $application['parent_id'] != $_SESSION['user']['id']) {
            $this->setFlashMessage('error', 'Application not found or access denied');
            redirect('/parent/portal');
            return;
        }
        
        $this->render('parent/application-status', [
            'pageTitle' => 'Application Status - Tiny Tots Creche',
            'application' => $application
        ]);
    }
    
    private function updateProfile() {
        $updateData = [
            'name' => sanitizeInput($_POST['name'] ?? ''),
            'surname' => sanitizeInput($_POST['surname'] ?? ''),
            'email' => sanitizeInput($_POST['email'] ?? ''),
            'phone' => sanitizeInput($_POST['phone'] ?? ''),
            'address' => sanitizeInput($_POST['address'] ?? ''),
            'city' => sanitizeInput($_POST['city'] ?? ''),
            'province' => sanitizeInput($_POST['province'] ?? ''),
            'postal_code' => sanitizeInput($_POST['postal_code'] ?? ''),
            'id_number' => sanitizeInput($_POST['id_number'] ?? ''),
            'relationship' => sanitizeInput($_POST['relationship'] ?? '')
        ];
        
        // Validate email
        $emailError = $this->validateEmail($updateData['email']);
        if ($emailError) {
            $this->setFlashMessage('error', $emailError);
            $this->render('parent/profile', [
                'pageTitle' => 'My Profile - Tiny Tots Creche',
                'user' => $_SESSION['user'],
                'errors' => ['email' => $emailError],
                'old' => array_merge($_SESSION['user'], $updateData)
            ]);
            return;
        }
        
        // Update user data
        $userModel = new UserModel('users.json');
        $updatedUser = $userModel->updateUser($_SESSION['user']['id'], $updateData);
        
        if ($updatedUser) {
            // Update session
            $_SESSION['user'] = $updatedUser;
            $this->setFlashMessage('success', 'Profile updated successfully!');
            redirect('/parent/portal');
        } else {
            $this->setFlashMessage('error', 'Failed to update profile. Please try again.');
            $this->render('parent/profile', [
                'pageTitle' => 'My Profile - Tiny Tots Creche',
                'user' => $_SESSION['user'],
                'errors' => ['general' => 'Failed to update profile'],
                'old' => array_merge($_SESSION['user'], $updateData)
            ]);
        }
    }
}
?>

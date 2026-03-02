<?php
class AdmissionController extends BaseController {
    private $admissionModel;
    
    public function __construct() {
        parent::__construct();
        $this->admissionModel = new AdmissionModel();
    }
    
    protected function generateCsrfToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    protected function validateCsrf() {
        return isset($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token']);
    }
    
    protected function json($data, $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
    
    public function index() {
        $this->render('admission/index', [
            'pageTitle' => 'Admission - Tiny Tots Creche',
            'gradeCategories' => [
                'toddlers' => ['label' => 'Toddlers (0-2 years)', 'min_age' => 0, 'max_age' => 2],
                'playgroup' => ['label' => 'Playgroup (2-3 years)', 'min_age' => 2, 'max_age' => 3],
                'preschool' => ['label' => 'Pre-School (3-4 years)', 'min_age' => 3, 'max_age' => 4],
                'grade_r' => ['label' => 'Grade R (4-5 years)', 'min_age' => 4, 'max_age' => 5],
                'grade_1' => ['label' => 'Grade 1 (5-6 years)', 'min_age' => 5, 'max_age' => 6],
                'foundation' => ['label' => 'Foundation Phase (6-7 years)', 'min_age' => 6, 'max_age' => 7]
            ],
            'csrfToken' => $this->generateCsrfToken()
        ]);
    }
    
    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/admission');
        }
        
        if (!$this->validateCsrf()) {
            $this->setFlashMessage('error', 'Invalid request. Please try again.');
            redirect('/admission');
        }
        
        try {
            $admissionData = $_POST;
            
            // Split full names into first and last names for model compatibility
            if (isset($admissionData['parentFullName'])) {
                $nameParts = explode(' ', trim($admissionData['parentFullName']), 2);
                $admissionData['parentFirstName'] = $nameParts[0];
                $admissionData['parentSurname'] = $nameParts[1] ?? '';
                unset($admissionData['parentFullName']);
            }
            
            if (isset($admissionData['childFullName'])) {
                $nameParts = explode(' ', trim($admissionData['childFullName']), 2);
                $admissionData['childFirstName'] = $nameParts[0];
                $admissionData['childSurname'] = $nameParts[1] ?? '';
                unset($admissionData['childFullName']);
            }
            
            // Map phone field to contactNumber for model compatibility
            if (isset($admissionData['phone'])) {
                $admissionData['contactNumber'] = $admissionData['phone'];
                unset($admissionData['phone']);
            }
            
            // Map address fields for model compatibility
            if (isset($admissionData['parentAddress'])) {
                $admissionData['residentialAddress'] = $admissionData['parentAddress'];
                unset($admissionData['parentAddress']);
            }
            
            // Handle file uploads
            $uploadedFiles = $this->handleFileUploads($_FILES);
            if (!empty($uploadedFiles['errors'])) {
                throw new Exception(implode(', ', $uploadedFiles['errors']));
            }
            
            // Merge uploaded file paths into admission data
            $admissionData = array_merge($admissionData, $uploadedFiles['files']);
            
            $admission = $this->admissionModel->createAdmission($admissionData);
            
            $this->setFlashMessage('success', 'Application submitted successfully! Your application ID is: ' . $admission['applicationID']);
            redirect('/admission/success?id=' . $admission['applicationID']);
            
        } catch (Exception $e) {
            $this->setFlashMessage('error', $e->getMessage());
            $this->render('admission/index', [
                'pageTitle' => 'Admission - Tiny Tots Creche',
                'errors' => ['general' => $e->getMessage()],
                'old' => $admissionData,
                'gradeCategories' => [
                    'toddlers' => ['label' => 'Toddlers (0-2 years)', 'min_age' => 0, 'max_age' => 2],
                    'playgroup' => ['label' => 'Playgroup (2-3 years)', 'min_age' => 2, 'max_age' => 3],
                    'preschool' => ['label' => 'Pre-School (3-4 years)', 'min_age' => 3, 'max_age' => 4],
                    'grade_r' => ['label' => 'Grade R (4-5 years)', 'min_age' => 4, 'max_age' => 5],
                    'grade_1' => ['label' => 'Grade 1 (5-6 years)', 'min_age' => 5, 'max_age' => 6],
                    'foundation' => ['label' => 'Foundation Phase (6-7 years)', 'min_age' => 6, 'max_age' => 7]
                ],
                'csrfToken' => $this->generateCsrfToken()
            ]);
        }
    }
    
    public function success() {
        $applicationId = $_GET['id'] ?? '';
        
        if (empty($applicationId)) {
            redirect('/admission');
        }
        
        $this->render('admission/success', [
            'pageTitle' => 'Application Submitted - Tiny Tots Creche',
            'applicationId' => $applicationId,
            'nextSteps' => [
                'You will receive an email confirmation within 24 hours',
                'Our admissions team will review your application',
                'You may be contacted for additional information or an interview',
                'Application status updates will be sent via email'
            ],
            'contactInfo' => [
                'phone' => '081 421 0084',
                'email' => 'mollerv40@gmail.com'
            ]
        ]);
    }
    
    public function list() {
        requireRole('headmaster');
        
        $status = $_GET['status'] ?? 'all';
        $allAdmissions = $this->admissionModel->getAllAdmissions();
        
        if ($status !== 'all') {
            $admissions = $this->admissionModel->getAdmissionsByStatus($status);
        } else {
            $admissions = $allAdmissions;
        }
        
        // Calculate statistics from the already fetched data
        $stats = [
            'total' => count($allAdmissions),
            'pending' => count(array_filter($allAdmissions, function($a) { return $a['status'] === 'Pending'; })),
            'approved' => count(array_filter($allAdmissions, function($a) { return $a['status'] === 'Approved'; })),
            'rejected' => count(array_filter($allAdmissions, function($a) { return $a['status'] === 'Rejected'; }))
        ];
        
        $this->render('admission/list', [
            'pageTitle' => 'Admission Applications - Tiny Tots Creche',
            'admissions' => $admissions,
            'status' => $status,
            'stats' => $stats,
            'gradeCategories' => [
                'grade_r' => 'Grade R',
                'grade_1' => 'Grade 1',
                'grade_2' => 'Grade 2',
                'grade_3' => 'Grade 3'
            ]
        ]);
    }
    
    public function view() {
        requireRole('headmaster');
        
        $id = $_GET['id'] ?? '';
        if (empty($id)) {
            $this->setFlashMessage('error', 'Application ID is required');
            redirect('/admin/admissions');
        }
        
        $admission = $this->admissionModel->getAdmissionById($id);
        
        if (!$admission) {
            $this->setFlashMessage('error', 'Application not found');
            redirect('/admin/admissions');
        }
        
        $this->render('admission/view', [
            'pageTitle' => 'View Application - Tiny Tots Creche',
            'admission' => $admission,
            'gradeCategories' => [
                'grade_r' => 'Grade R',
                'grade_1' => 'Grade 1',
                'grade_2' => 'Grade 2',
                'grade_3' => 'Grade 3'
            ]
        ]);
    }
    
    public function updateStatus() {
        requireRole('headmaster');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
        }
        
        $id = $_POST['id'] ?? '';
        $status = $_POST['status'] ?? '';
        
        if (empty($id) || empty($status)) {
            $this->json(['error' => 'Application ID and status are required'], 400);
        }
        
        if (!in_array($status, ['Pending', 'Approved', 'Rejected'])) {
            $this->json(['error' => 'Invalid status'], 400);
        }
        
        if ($this->admissionModel->updateAdmissionStatus($id, $status)) {
            $this->json(['success' => true, 'message' => 'Application status updated successfully']);
        } else {
            $this->json(['error' => 'Failed to update application status'], 500);
        }
    }
    
    private function handleFileUploads($files) {
        $result = ['files' => [], 'errors' => []];
        $uploadDir = dirname(__DIR__) . '/uploads/admissions/';
        
        $allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        
        $requiredFiles = [
            'childBirthCertificate' => 'Child ID / Birth Certificate',
            'parentIdDocument' => 'Parent ID',
            'clinicalReport' => 'Clinical Report'
        ];
        
        $optionalFiles = [
            'previousSchoolReport' => 'Previous School Report'
        ];
        
        $allFiles = array_merge($requiredFiles, $optionalFiles);
        
        foreach ($allFiles as $fileField => $displayName) {
            if (!isset($files[$fileField]) || $files[$fileField]['error'] === UPLOAD_ERR_NO_FILE) {
                if (isset($requiredFiles[$fileField])) {
                    $result['errors'][] = "{$displayName} is required";
                }
                continue;
            }
            
            $file = $files[$fileField];
            
            // Check for upload errors
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $result['errors'][] = "Error uploading {$displayName}: " . $this->getUploadErrorMessage($file['error']);
                continue;
            }
            
            // Validate file type
            if (!in_array($file['type'], $allowedTypes)) {
                $result['errors'][] = "{$displayName}: Invalid file type. Only PDF, JPG, PNG allowed.";
                continue;
            }
            
            // Validate file size
            if ($file['size'] > $maxSize) {
                $result['errors'][] = "{$displayName}: File size exceeds 2MB limit.";
                continue;
            }
            
            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid($fileField . '_', true) . '.' . $extension;
            $filepath = $uploadDir . $filename;
            
            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                $result['files'][$fileField] = 'uploads/admissions/' . $filename;
            } else {
                $result['errors'][] = "Failed to save {$displayName}";
            }
        }
        
        return $result;
    }
    
    private function getUploadErrorMessage($errorCode) {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                return "The uploaded file exceeds the upload_max_filesize directive in php.ini";
            case UPLOAD_ERR_FORM_SIZE:
                return "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
            case UPLOAD_ERR_PARTIAL:
                return "The uploaded file was only partially uploaded";
            case UPLOAD_ERR_NO_FILE:
                return "No file was uploaded";
            case UPLOAD_ERR_NO_TMP_DIR:
                return "Missing a temporary folder";
            case UPLOAD_ERR_CANT_WRITE:
                return "Failed to write file to disk";
            case UPLOAD_ERR_EXTENSION:
                return "A PHP extension stopped the file upload";
            default:
                return "Unknown upload error";
        }
    }
    
    public function delete() {
        requireRole('headmaster');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
        }
        
        $id = $_POST['id'] ?? '';
        
        if (empty($id)) {
            $this->json(['error' => 'Application ID is required'], 400);
        }
        
        if ($this->admissionModel->deleteAdmission($id)) {
            $this->json(['success' => true, 'message' => 'Application deleted successfully']);
        } else {
            $this->json(['error' => 'Failed to delete application'], 500);
        }
    }
}
?>

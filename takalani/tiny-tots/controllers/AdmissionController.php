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
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            // Store the intended destination for redirect after login
            $_SESSION['redirect_after_login'] = '/admission';
            $this->setFlashMessage('info', 'Please login or register to access the admission form.');
            redirect('/login');
        }
        
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
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $this->setFlashMessage('error', 'Please login to submit your application.');
            redirect('/login');
        }
        
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
    
    public function dashboard() {
        requireRole('headmaster');
        
        $allAdmissions = $this->admissionModel->getAllAdmissions();
        
        // Calculate statistics
        $stats = [
            'total' => count($allAdmissions),
            'pending' => count(array_filter($allAdmissions, function($a) { return $a['status'] === 'Pending'; })),
            'approved' => count(array_filter($allAdmissions, function($a) { return $a['status'] === 'Approved'; })),
            'rejected' => count(array_filter($allAdmissions, function($a) { return $a['status'] === 'Rejected'; }))
        ];
        
        // Get recent applications (last 5)
        $recentApplications = array_slice($allAdmissions, 0, 5);
        
        $this->render('admin/dashboard', [
            'pageTitle' => 'Headmaster Dashboard - Tiny Tots Creche',
            'stats' => $stats,
            'recentApplications' => $recentApplications,
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
    
    public function list() {
        requireRole('headmaster');
        
        $status = $_GET['status'] ?? 'all';
        $search = $_GET['search'] ?? '';
        $sortBy = $_GET['sortBy'] ?? 'date';
        
        $allAdmissions = $this->admissionModel->getAllAdmissions();
        
        // Filter by status
        if ($status !== 'all') {
            $allAdmissions = array_filter($allAdmissions, function($a) use ($status) {
                return strtolower($a['status']) === strtolower($status);
            });
        }
        
        // Apply search filter
        if (!empty($search)) {
            $searchLower = strtolower($search);
            $allAdmissions = array_filter($allAdmissions, function($a) use ($searchLower) {
                return (
                    strpos(strtolower($a['applicationID']), $searchLower) !== false ||
                    strpos(strtolower($a['childFirstName'] . ' ' . $a['childSurname']), $searchLower) !== false ||
                    strpos(strtolower($a['parentFirstName'] . ' ' . $a['parentSurname']), $searchLower) !== false
                );
            });
        }
        
        // Sort applications
        usort($allAdmissions, function($a, $b) use ($sortBy) {
            switch ($sortBy) {
                case 'name':
                    return strcmp(($a['childFirstName'] . ' ' . $a['childSurname']), ($b['childFirstName'] . ' ' . $b['childSurname']));
                case 'status':
                    return strcmp($a['status'], $b['status']);
                case 'date':
                default:
                    $dateA = $a['submittedAt'] ?? $a['submitted_at'] ?? $a['submittedAt'] ?? '1970-01-01';
                    $dateB = $b['submittedAt'] ?? $b['submitted_at'] ?? $b['submittedAt'] ?? '1970-01-01';
                    return strtotime($dateB) - strtotime($dateA);
            }
        });
        
        // Calculate statistics from all admissions (not filtered)
        $allAdmissionsForStats = $this->admissionModel->getAllAdmissions();
        $stats = [
            'total' => count($allAdmissionsForStats),
            'pending' => count(array_filter($allAdmissionsForStats, function($a) { return $a['status'] === 'Pending'; })),
            'approved' => count(array_filter($allAdmissionsForStats, function($a) { return $a['status'] === 'Approved'; })),
            'rejected' => count(array_filter($allAdmissionsForStats, function($a) { return $a['status'] === 'Rejected'; }))
        ];
        
        $this->render('admin/admissions', [
            'pageTitle' => 'Admission Applications - Tiny Tots Creche',
            'admissions' => $allAdmissions,
            'status' => $status,
            'stats' => $stats,
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
        
        $this->render('admin/application-details', [
            'pageTitle' => 'Application Details - Tiny Tots Creche',
            'admission' => $admission,
            'csrfToken' => $this->generateCsrfToken(),
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
    
    public function updateStatus() {
        requireRole('headmaster');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
        }
        
        $id = $_POST['id'] ?? '';
        $status = $_POST['status'] ?? '';
        $notes = $_POST['notes'] ?? '';
        
        if (empty($id) || empty($status)) {
            $this->json(['error' => 'Application ID and status are required'], 400);
        }
        
        if (!in_array($status, ['Pending', 'Approved', 'Rejected'])) {
            $this->json(['error' => 'Invalid status'], 400);
        }
        
        // Get current admission for audit trail
        $currentAdmission = $this->admissionModel->getAdmissionById($id);
        if (!$currentAdmission) {
            $this->json(['error' => 'Application not found'], 404);
        }
        
        // Update status with audit trail
        if ($this->admissionModel->updateAdmissionStatus($id, $status, $notes, $_SESSION['user']['name'] ?? 'Headmaster')) {
            // Send notification to parent (in real implementation)
            $this->sendStatusNotification($currentAdmission, $status, $notes);
            
            $this->json([
                'success' => true, 
                'message' => 'Application status updated successfully',
                'newStatus' => $status
            ]);
        } else {
            $this->json(['error' => 'Failed to update application status'], 500);
        }
    }
    
    private function sendStatusNotification($admission, $newStatus, $notes) {
        // In a real implementation, this would send email/SMS
        // For now, we'll just log the notification
        $notification = [
            'applicationId' => $admission['applicationID'],
            'parentEmail' => $admission['emailAddress'],
            'parentName' => $admission['parentFirstName'] . ' ' . $admission['parentSurname'],
            'childName' => $admission['childFirstName'] . ' ' . $admission['childSurname'],
            'status' => $newStatus,
            'notes' => $notes,
            'sentAt' => date('Y-m-d H:i:s'),
            'type' => 'status_change'
        ];
        
        // Log notification (in real implementation, would send email/SMS)
        error_log('Notification sent: ' . json_encode($notification));
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
    
    public function export() {
        requireRole('headmaster');
        
        $status = $_GET['status'] ?? 'all';
        $format = $_GET['format'] ?? 'csv';
        
        $allAdmissions = $this->admissionModel->getAllAdmissions();
        
        // Filter by status if specified
        if ($status !== 'all') {
            $allAdmissions = array_filter($allAdmissions, function($a) use ($status) {
                return strtolower($a['status']) === strtolower($status);
            });
        }
        
        if (empty($allAdmissions)) {
            $this->json(['error' => 'No applications found to export'], 404);
        }
        
        // Prepare data for export
        $exportData = [];
        foreach ($allAdmissions as $admission) {
            $exportData[] = [
                'Application ID' => $admission['applicationID'],
                'Child Name' => $admission['childFirstName'] . ' ' . $admission['childSurname'],
                'Parent Name' => $admission['parentFirstName'] . ' ' . $admission['parentSurname'],
                'Email' => $admission['emailAddress'],
                'Phone' => $admission['contactNumber'],
                'Date of Birth' => $admission['dateOfBirth'],
                'Age' => $admission['age'],
                'Gender' => $admission['childGender'],
                'Grade' => $gradeCategories[$admission['gradeApplyingFor']] ?? 'N/A',
                'Status' => $admission['status'],
                'Submitted Date' => $admission['submittedAt'] ?? $admission['submitted_at'] ?? 'N/A',
                'Updated Date' => $admission['updatedAt'] ?? $admission['updated_at'] ?? $admission['submittedAt'] ?? $admission['submitted_at'] ?? 'N/A'
            ];
        }
        
        switch ($format) {
            case 'csv':
                $this->exportCSV($exportData, 'admissions_' . $status . '_' . date('Y-m-d') . '.csv');
                break;
            case 'json':
                $this->exportJSON($exportData, 'admissions_' . $status . '_' . date('Y-m-d') . '.json');
                break;
            default:
                $this->json(['error' => 'Invalid export format'], 400);
        }
    }
    
    private function exportCSV($data, $filename) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Write CSV header
        fputcsv($output, array_keys($data[0]));
        
        // Write CSV data
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        fclose($output);
        exit;
    }
    
    private function exportJSON($data, $filename) {
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
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

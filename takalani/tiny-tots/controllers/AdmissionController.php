<?php
class AdmissionController extends BaseController {
    private $admissionModel;
    
    public function __construct() {
        parent::__construct();
        $this->admissionModel = new AdmissionModel();
    }
    
    public function index() {
        $this->render('admission/index', [
            'pageTitle' => 'Admission - Tiny Tots Creche',
            'gradeCategories' => [
                'grade_r' => ['label' => 'Grade R (4-5 years)', 'description' => 'Foundation phase preparation'],
                'grade_1' => ['label' => 'Grade 1 (6-7 years)', 'description' => 'Beginning of formal education'],
                'grade_2' => ['label' => 'Grade 2 (7-8 years)', 'description' => 'Building foundational skills'],
                'grade_3' => ['label' => 'Grade 3 (8-9 years)', 'description' => 'Intermediate phase preparation']
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
                    'grade_r' => ['label' => 'Grade R (4-5 years)', 'description' => 'Foundation phase preparation'],
                    'grade_1' => ['label' => 'Grade 1 (6-7 years)', 'description' => 'Beginning of formal education'],
                    'grade_2' => ['label' => 'Grade 2 (7-8 years)', 'description' => 'Building foundational skills'],
                    'grade_3' => ['label' => 'Grade 3 (8-9 years)', 'description' => 'Intermediate phase preparation']
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
        $admissions = $this->admissionModel->getAllAdmissions();
        
        if ($status !== 'all') {
            $admissions = $this->admissionModel->getAdmissionsByStatus($status);
        }
        
        // Calculate statistics
        $stats = [
            'total' => count($this->admissionModel->getAllAdmissions()),
            'pending' => count($this->admissionModel->getAdmissionsByStatus('Pending')),
            'approved' => count($this->admissionModel->getAdmissionsByStatus('Approved')),
            'rejected' => count($this->admissionModel->getAdmissionsByStatus('Rejected'))
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

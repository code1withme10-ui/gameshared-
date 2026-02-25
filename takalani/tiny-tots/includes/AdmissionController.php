<?php
// MVC Controller for Admission
class AdmissionController {
    private $admissionModel;
    private $error = '';
    private $success = '';
    private $submissionSummary = null;
    
    public function __construct() {
        $this->admissionModel = new AdmissionModel();
        global $gradeCategories;
        $this->gradeCategories = $gradeCategories;
    }
    
    public function index() {
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleSubmission();
        }
        
        // Include header
        require_once 'includes/header.php';
        
        // Include the admission view
        $this->renderAdmissionView();
        
        // Include footer
        require_once 'includes/footer.php';
    }
    
    private function handleSubmission() {
        try {
            // Validate required fields
            $requiredFields = [
                'parentFirstName', 'parentSurname', 'contactNumber', 'emailAddress', 
                'parentIdNumber', 'residentialAddress', 'childFirstName', 'childSurname', 
                'dateOfBirth', 'childGender', 'gradeApplyingFor'
            ];
            
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("Please fill in all required fields.");
                }
            }
            
            // Validate email
            if (!filter_var($_POST['emailAddress'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Please enter a valid email address.");
            }
            
            // Validate phone number
            if (!preg_match('/^[0-9\s\-\+\(\)]{10,}$/', $_POST['contactNumber'])) {
                throw new Exception("Please enter a valid phone number.");
            }
            
            // Validate ID number
            if (!preg_match('/^[0-9]{13}$/', $_POST['parentIdNumber'])) {
                throw new Exception("Please enter a valid 13-digit ID number.");
            }
            
            // Validate date of birth
            $dateOfBirth = $_POST['dateOfBirth'];
            $age = $this->calculateAge($dateOfBirth);
            
            if ($age < 3 || $age > 9) {
                throw new Exception("Child's age must be between 3 and 9 years.");
            }
            
            // Validate grade for age
            $gradeValidation = $this->validateGradeForAge($_POST['gradeApplyingFor'], $age);
            if (!$gradeValidation['valid']) {
                throw new Exception($gradeValidation['message']);
            }
            
            // Create admission using MVC model
            $admissionData = $_POST;
            $newAdmission = $this->admissionModel->createAdmission($admissionData);
            
            $this->success = "Application submitted successfully! Your application ID is: " . $newAdmission['applicationID'];
            $this->submissionSummary = $newAdmission;
            
        } catch (Exception $e) {
            $this->error = $e->getMessage();
        }
    }
    
    private function renderAdmissionView() {
        global $gradeCategories;
        ?>
        <main class="home-container">
            <section class="admission-hero">
                <h1>📝 Admission Application</h1>
                <p>Begin your child's educational journey with Tiny Tots Creche</p>
            </section>

            <div class="admission-container">
                <div class="admission-form-container">
                    <div class="form-header">
                        <h2>📝 Tiny Tots Creche Admission Application</h2>
                        <p>Please complete all required fields. Applications are reviewed on a first-come, first-served basis.</p>
                    </div>

                    <?php if ($this->error): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-triangle"></i>
                            <?= htmlspecialchars($this->error) ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->success): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <?= htmlspecialchars($this->success) ?>
                        </div>
                        
                        <?php if ($this->submissionSummary): ?>
                            <div class="submission-summary">
                                <h3>📋 Application Summary</h3>
                                <p><strong>Application ID:</strong> <?= htmlspecialchars($this->submissionSummary['applicationID']) ?></p>
                                <p><strong>Child Name:</strong> <?= htmlspecialchars($this->submissionSummary['childFirstName'] . ' ' . $this->submissionSummary['childSurname']) ?></p>
                                <p><strong>Grade:</strong> <?= htmlspecialchars($gradeCategories[$this->submissionSummary['gradeApplyingFor']]['label']) ?></p>
                                <p><strong>Status:</strong> <?= htmlspecialchars($this->submissionSummary['status']) ?></p>
                                <p><strong>Submitted:</strong> <?= date('F j, Y, g:i a', strtotime($this->submissionSummary['submittedAt'])) ?></p>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (!$this->success): ?>
                        <form id="admissionForm" method="POST" class="admission-form" enctype="multipart/form-data">
                            <!-- Parent/Guardian Information -->
                            <fieldset class="form-section">
                                <legend><i class="fas fa-user"></i> Parent/Guardian Information</legend>
                                
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="parentFirstName">First Name *</label>
                                        <input type="text" id="parentFirstName" name="parentFirstName" required
                                               value="<?= htmlspecialchars($_POST['parentFirstName'] ?? '') ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="parentSurname">Surname *</label>
                                        <input type="text" id="parentSurname" name="parentSurname" required
                                               value="<?= htmlspecialchars($_POST['parentSurname'] ?? '') ?>">
                                    </div>
                                </div>
                                
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="contactNumber">Contact Number *</label>
                                        <input type="tel" id="contactNumber" name="contactNumber" required
                                               pattern="[0-9]{10}" placeholder="e.g., 0812345678"
                                               value="<?= htmlspecialchars($_POST['contactNumber'] ?? '') ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="emailAddress">Email Address *</label>
                                        <input type="email" id="emailAddress" name="emailAddress" required
                                               value="<?= htmlspecialchars($_POST['emailAddress'] ?? '') ?>">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="parentIdNumber">ID Number *</label>
                                    <input type="text" id="parentIdNumber" name="parentIdNumber" required
                                           pattern="[0-9]{13}" placeholder="13-digit South African ID" 
                                           value="<?= htmlspecialchars($_POST['parentIdNumber'] ?? '') ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="residentialAddress">Residential Address *</label>
                                    <input type="text" id="residentialAddress" name="residentialAddress" required
                                           value="<?= htmlspecialchars($_POST['residentialAddress'] ?? '') ?>">
                                </div>
                            </fieldset>

                            <!-- Child Information -->
                            <fieldset class="form-section">
                                <legend><i class="fas fa-child"></i> Child Information</legend>
                                
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="childFirstName">First Name *</label>
                                        <input type="text" id="childFirstName" name="childFirstName" required
                                               value="<?= htmlspecialchars($_POST['childFirstName'] ?? '') ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="childSurname">Surname *</label>
                                        <input type="text" id="childSurname" name="childSurname" required
                                               value="<?= htmlspecialchars($_POST['childSurname'] ?? '') ?>">
                                    </div>
                                </div>
                                
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="dateOfBirth">Date of Birth *</label>
                                        <input type="date" id="dateOfBirth" name="dateOfBirth" required
                                               value="<?= htmlspecialchars($_POST['dateOfBirth'] ?? '') ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="childGender">Gender *</label>
                                        <select id="childGender" name="childGender" required>
                                            <option value="">Select Gender</option>
                                            <option value="male" <?= ($_POST['childGender'] ?? '') === 'male' ? 'selected' : '' ?>>Male</option>
                                            <option value="female" <?= ($_POST['childGender'] ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="gradeApplyingFor">Grade Applying For *</label>
                                    <select id="gradeApplyingFor" name="gradeApplyingFor" required>
                                        <option value="">Select Grade</option>
                                        <?php foreach ($gradeCategories as $key => $grade): ?>
                                            <option value="<?= $key ?>" <?= ($_POST['gradeApplyingFor'] ?? '') === $key ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($grade['label']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="form-help">Age requirements will be validated automatically</small>
                                </div>
                            </fieldset>

                            <!-- Emergency Contact -->
                            <fieldset class="form-section">
                                <legend><i class="fas fa-phone-alt"></i> Emergency Contact</legend>
                                
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="emergencyContactName">Emergency Contact Name</label>
                                        <input type="text" id="emergencyContactName" name="emergencyContactName"
                                               value="<?= htmlspecialchars($_POST['emergencyContactName'] ?? '') ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="emergencyContactPhone">Emergency Contact Phone</label>
                                        <input type="tel" id="emergencyContactPhone" name="emergencyContactPhone"
                                               value="<?= htmlspecialchars($_POST['emergencyContactPhone'] ?? '') ?>">
                                    </div>
                                </div>
                            </fieldset>

                            <!-- Additional Information -->
                            <fieldset class="form-section">
                                <legend><i class="fas fa-heart"></i> Additional Information</legend>
                                
                                <div class="form-group">
                                    <label for="specialNeeds">Special Needs or Medical Conditions</label>
                                    <textarea id="specialNeeds" name="specialNeeds" rows="4"
                                              placeholder="Please describe any special needs, allergies, or medical conditions..."><?= htmlspecialchars($_POST['specialNeeds'] ?? '') ?></textarea>
                                    <small class="form-help">This information helps us provide the best care for your child</small>
                                </div>
                            </fieldset>

                            <!-- Terms and Submit -->
                            <fieldset class="form-section">
                                <div class="form-group">
                                    <label class="checkbox-label">
                                        <input type="checkbox" id="terms" name="terms" required>
                                        <span class="checkmark"></span>
                                        I certify that all information provided is accurate and complete. I understand that false information may result in the rejection of this application.
                                    </label>
                                </div>
                            </fieldset>

                            <div class="form-actions">
                                <button type="submit" class="cta-btn">
                                    <i class="fas fa-paper-plane"></i> Submit Application
                                </button>
                                <button type="reset" class="btn-secondary">
                                    <i class="fas fa-redo"></i> Reset Form
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </main>
        <?php
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
?>

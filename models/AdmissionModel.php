<?php
class AdmissionModel extends BaseModel {
    public function __construct() {
        parent::__construct(ADMISSIONS_FILE);
    }
    
    public function createAdmission($admissionData) {
        $requiredFields = [
            'parentFirstName', 'parentSurname', 'contactNumber', 'emailAddress',
            'parentIdNumber', 'residentialAddress', 'childFirstName', 'childSurname',
            'dateOfBirth', 'childIdNumber', 'childGender', 'gradeApplyingFor', 'emergencyContactName',
            'emergencyContactPhone', 'relationshipToChild'
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
        
        if (!$this->validateIdNumber($admissionData['childIdNumber'])) {
            throw new Exception("Invalid child ID number");
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
            'parent_id' => $admissionData['parent_id'] ?? null, // Use parent_id from form data
            'parentFirstName' => $admissionData['parentFirstName'],
            'parentSurname' => $admissionData['parentSurname'],
            'contactNumber' => $admissionData['contactNumber'],
            'emailAddress' => $admissionData['emailAddress'],
            'parentIdNumber' => $admissionData['parentIdNumber'],
            'residentialAddress' => $admissionData['residentialAddress'],
            'relationshipToChild' => $admissionData['relationshipToChild'],
            'childFirstName' => $admissionData['childFirstName'],
            'childSurname' => $admissionData['childSurname'],
            'dateOfBirth' => $admissionData['dateOfBirth'],
            'childIdNumber' => $admissionData['childIdNumber'],
            'childGender' => $admissionData['childGender'],
            'gradeApplyingFor' => $admissionData['gradeApplyingFor'],
            'emergencyContactName' => $admissionData['emergencyContactName'],
            'emergencyContactPhone' => $admissionData['emergencyContactPhone'],
            'emergencyContactAddress' => $admissionData['emergencyContactAddress'] ?? '',
            'childAddress' => $admissionData['childAddress'] ?? '',
            'transportation' => $admissionData['transportation'] ?? 'none',
            'specialNeeds' => $admissionData['specialNeeds'] ?? '',
            'status' => 'pending',
            'submitted_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'age' => $age,
            'child_name' => $admissionData['childFirstName'] . ' ' . $admissionData['childSurname'],
            'child_age' => $age,
            'grade' => $admissionData['gradeApplyingFor']
        ];
        
        // Add documents if provided
        if (isset($admissionData['documents']) && is_array($admissionData['documents'])) {
            $newAdmission['documents'] = $admissionData['documents'];
        }
        
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
    
    public function getAdmissionsByStatus($status) {
        $admissions = $this->readJsonFile();
        return array_filter($admissions, function($admission) use ($status) {
            return $admission['status'] === $status;
        });
    }
    
    public function updateAdmissionStatus($id, $status, $notes = '', $changedBy = '') {
        $admissions = $this->readJsonFile();
        $updated = false;
        
        foreach ($admissions as &$admission) {
            if ($admission['id'] === $id) {
                $oldStatus = $admission['status'];
                $admission['status'] = $status;
                $admission['updatedAt'] = date('Y-m-d H:i:s');
                
                // Add to status history
                if (!isset($admission['statusHistory'])) {
                    $admission['statusHistory'] = [];
                }
                
                $admission['statusHistory'][] = [
                    'date' => date('Y-m-d H:i:s'),
                    'oldStatus' => $oldStatus,
                    'newStatus' => $status,
                    'notes' => $notes,
                    'changedBy' => $changedBy
                ];
                
                $updated = true;
                break;
            }
        }
        
        if ($updated) {
            $this->writeJsonFile($admissions);
            return true;
        }
        
        return false;
    }
    
    public function deleteAdmission($id) {
        $admissions = $this->readJsonFile();
        $filteredAdmissions = array_filter($admissions, function($admission) use ($id) {
            return $admission['id'] !== $id;
        });
        
        if (count($filteredAdmissions) < count($admissions)) {
            $this->writeJsonFile(array_values($filteredAdmissions));
            return true;
        }
        
        return false;
    }
    
    private function generateApplicationId() {
        $year = date('Y');
        $sequence = random_int(1000, 9999);
        return "TT{$year}{$sequence}";
    }
    
    private function calculateAge($dateOfBirth) {
        $dob = new DateTime($dateOfBirth);
        $today = new DateTime();
        return $today->diff($dob)->y;
    }
    
    private function validateGradeForAge($grade, $age) {
        $gradeCategories = [
            'toddlers' => ['min_age' => 0, 'max_age' => 2],
            'playgroup' => ['min_age' => 2, 'max_age' => 3],
            'preschool' => ['min_age' => 3, 'max_age' => 4],
            'grade_r' => ['min_age' => 4, 'max_age' => 5],
            'grade_1' => ['min_age' => 5, 'max_age' => 6],
            'foundation' => ['min_age' => 6, 'max_age' => 7]
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
    
    public function getApplicationsByParent($parentId) {
        $admissions = $this->readJsonFile();
        $parentApplications = [];
        
        foreach ($admissions as $admission) {
            if (isset($admission['parent_id']) && $admission['parent_id'] === $parentId) {
                $parentApplications[] = $admission;
            }
        }
        
        return $parentApplications;
    }
    
    public function getApplicationById($applicationId) {
        $admissions = $this->readJsonFile();
        
        foreach ($admissions as $admission) {
            if (isset($admission['id']) && $admission['id'] === $applicationId) {
                return $admission;
            }
        }
        
        return false;
    }
}
?>

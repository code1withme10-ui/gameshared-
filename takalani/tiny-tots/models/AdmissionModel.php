<?php
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
    
    public function getAdmissionsByStatus($status) {
        $admissions = $this->readJsonFile();
        return array_filter($admissions, function($admission) use ($status) {
            return $admission['status'] === $status;
        });
    }
    
    public function updateAdmissionStatus($id, $status) {
        $admissions = $this->readJsonFile();
        $updated = false;
        
        foreach ($admissions as &$admission) {
            if ($admission['id'] === $id) {
                $admission['status'] = $status;
                $admission['updatedAt'] = date('Y-m-d H:i:s');
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
?>

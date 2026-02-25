<?php
abstract class BaseModel {
    protected $dataFile;
    
    public function __construct($filename) {
        $this->dataFile = $filename;
        $this->ensureDataFileExists();
    }
    
    protected function readJsonFile() {
        if (!file_exists($this->dataFile)) {
            return [];
        }
        $json = file_get_contents($this->dataFile);
        return json_decode($json, true) ?: [];
    }
    
    protected function writeJsonFile($data) {
        $json = json_encode($data, JSON_PRETTY_PRINT);
        return file_put_contents($this->dataFile, $json) !== false;
    }
    
    protected function ensureDataFileExists() {
        if (!file_exists($this->dataFile)) {
            $this->writeJsonFile([]);
        }
    }
    
    protected function generateId() {
        return uniqid('tt_', true);
    }
    
    protected function validateRequired($data, $requiredFields) {
        $missing = [];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $missing[] = $field;
            }
        }
        return $missing;
    }
    
    protected function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    protected function validatePhone($phone) {
        return preg_match('/^[0-9\s\-\+\(\)]{10,}$/', $phone);
    }
    
    protected function validateIdNumber($idNumber) {
        return preg_match('/^[0-9]{13}$/', $idNumber);
    }
}
?>

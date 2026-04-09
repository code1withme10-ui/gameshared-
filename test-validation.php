<?php
require_once 'config/config.php';
require_once 'models/BaseModel.php';
require_once 'models/UserModel.php';

$userModel = new UserModel();
echo "validateEmail method exists: " . (method_exists($userModel, 'validateEmail') ? 'YES' : 'NO') . "\n";
echo "validateIdNumber method exists: " . (method_exists($userModel, 'validateIdNumber') ? 'YES' : 'NO') . "\n";

// Test validation
$emailTest = $userModel->validateEmail('test@example.com');
echo "Email validation result: " . ($emailTest ? 'VALID' : 'INVALID') . "\n";

$idTest = $userModel->validateIdNumber('12345678');
echo "ID validation result: " . ($idTest ? 'VALID' : 'INVALID') . "\n";
?>

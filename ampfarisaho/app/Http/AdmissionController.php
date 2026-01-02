<?php
class AdmissionController
{
    public $errors = [];
    public $success_message = '';

    public function handle()
    {
        require_once __DIR__ . '/../../includes/functions.php';

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $this->processAdmission();
        }
    }

    private function processAdmission()
    {
        $allowed_ext = ["pdf","jpg","jpeg","png"];
        $parents = readJSON(__DIR__ . '/../../data/parents.json');
        $children = readJSON(__DIR__ . '/../../data/children.json');

        // Validate parent login fields
        $required_parent = ['parent_username','parent_password','parent_name','parent_relationship','parent_email','parent_phone','parent_address'];
        foreach($required_parent as $f) {
            if(empty($_POST[$f])) $this->errors[] = ucfirst(str_replace("_", " ", $f))." is required.";
        }

        if(!empty($_POST['parent_email']) && !filter_var($_POST['parent_email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Invalid email format.";
        }

        if(!empty($_POST['parent_phone']) && !preg_match("/^[0-9]{10}$/", $_POST['parent_phone'])) {
            $this->errors[] = "Phone number must be 10 digits.";
        }

        // Validate documents
        foreach(["birth_cert","parent_id"] as $file) {
            if(!isset($_FILES[$file]) || $_FILES[$file]["error"] !== 0) {
                $this->errors[] = "Missing required document: $file";
            } else {
                $ext = strtolower(pathinfo($_FILES[$file]["name"], PATHINFO_EXTENSION));
                if(!in_array($ext,$allowed_ext)) $this->errors[] = "Invalid file type for $file";
                if($_FILES[$file]["size"] > 2*1024*1024) $this->errors[] = "$file exceeds 2MB.";
            }
        }

        if(empty($this->errors)) {
            $upload_dir = __DIR__ . "/../../uploads/" . $_POST['parent_username'] . "/";
            if(!is_dir($upload_dir)) mkdir($upload_dir,0777,true);

            $birth_cert_file = $upload_dir . "birth_cert_" . time() . "_" . $_FILES['birth_cert']['name'];
            move_uploaded_file($_FILES['birth_cert']['tmp_name'], $birth_cert_file);

            $parent_id_file = $upload_dir . "parent_id_" . time() . "_" . $_FILES['parent_id']['name'];
            move_uploaded_file($_FILES['parent_id']['tmp_name'], $parent_id_file);

            // Save parent
            $parents[] = [
                "username" => $_POST['parent_username'],
                "password" => $_POST['parent_password'],
                "email" => $_POST['parent_email'],
                "full_name" => $_POST['parent_name'],
                "phone" => $_POST['parent_phone'],
                "relationship" => $_POST['parent_relationship'],
                "address" => $_POST['parent_address']
            ];
            writeJSON(__DIR__ . '/../../data/parents.json', $parents);

            // Save child
            $children[] = [
                "parent_username" => $_POST['parent_username'],
                "child_name" => $_POST['child_name'],
                "dob" => $_POST['child_dob'],
                "gender" => $_POST['child_gender'],
                "grade_category" => $_POST['grade_category'],
                "address" => $_POST['child_address'],
                "birth_certificate" => $birth_cert_file,
                "parent_id" => $parent_id_file,
                "status" => "Awaiting approval"
            ];
            writeJSON(__DIR__ . '/../../data/children.json', $children);

            $this->success_message = "Admission submitted successfully! Your child is now awaiting approval.";
        }
    }
}




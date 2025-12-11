<?php
class ParentController
{
    public $errors = [];
    public $success_message = '';
    public $my_children = [];
    public $parent_info = null;

    public function handle()
    {
        session_start();
        require_once __DIR__ . '/../../includes/functions.php';
        require_once __DIR__ . '/../../includes/auth.php';

        requireParentLogin();
        $parents = readJSON(__DIR__ . '/../../data/parents.json');
        $children = readJSON(__DIR__ . '/../../data/children.json');

        $parent_username = $_SESSION['parent'] ?? '';

        // Get parent info
        foreach ($parents as $p) {
            if (isset($p['username']) && $p['username'] === $parent_username) {
                $this->parent_info = $p;
                break;
            }
        }

        // Filter children for this parent
        $this->my_children = array_filter($children, fn($c) => isset($c['parent_username']) && $c['parent_username'] === $parent_username);

        // Handle new child submission
        if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['new_child'])) {
            $this->handleNewChild($parent_username, $children);
        }
    }

    private function handleNewChild($parent_username, &$children)
    {
        $allowed_ext = ["pdf","jpg","jpeg","png"];

        // Validate required fields
        $required_fields = ['child_name','child_dob','child_gender','grade_category','child_address'];
        foreach($required_fields as $f) {
            if (empty($_POST[$f])) {
                $this->errors[] = "All child fields are required.";
                break;
            }
        }

        // Validate documents
        foreach(["birth_cert","parent_id"] as $file) {
            if (!isset($_FILES[$file]) || $_FILES[$file]["error"] !== 0) {
                $this->errors[] = "Missing required document: $file";
            } else {
                $ext = strtolower(pathinfo($_FILES[$file]["name"], PATHINFO_EXTENSION));
                if (!in_array($ext, $allowed_ext)) $this->errors[] = "Invalid file type for $file.";
                if ($_FILES[$file]["size"] > 2*1024*1024) $this->errors[] = "$file exceeds 2MB.";
            }
        }

        if(empty($this->errors)) {
            $upload_dir = __DIR__ . "/../../uploads/" . $parent_username . "/";
            if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

            $birth_cert_file = $upload_dir . "birth_cert_" . time() . "_" . $_FILES['birth_cert']['name'];
            move_uploaded_file($_FILES['birth_cert']['tmp_name'], $birth_cert_file);

            $parent_id_file = $upload_dir . "parent_id_" . time() . "_" . $_FILES['parent_id']['name'];
            move_uploaded_file($_FILES['parent_id']['tmp_name'], $parent_id_file);

            $children[] = [
                "parent_username" => $parent_username,
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
            $this->success_message = "New child application submitted successfully!";
            
            // Update my_children for immediate display
            $this->my_children = array_filter($children, fn($c) => $c['parent_username'] === $parent_username);
        }
    }
}

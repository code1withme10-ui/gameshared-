<?php
// REGISTRATION/ADMISSION PAGE: registration.php

// CRITICAL: Start the session at the very top
session_start();

// Include database connection file (must define the $pdo object)
require_once 'db_connect.php'; 

// Initialize variables
$success = false; 
$registration_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Basic Validation and Variable Setup
    $child_name = trim($_POST['child_name'] ?? '');
    $dob = trim($_POST['dob'] ?? '');
    $parent_name = trim($_POST['parent_name'] ?? '');
    
    // The email field from the form is mapped to the 'username' column in the 'users' table
    $parent_email = trim($_POST['parent_email'] ?? ''); 
    
    $parent_password = $_POST['parent_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $enrollment_type = trim($_POST['enrollment_type'] ?? '');
    $start_date = trim($_POST['start_date'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    // Server-side Password Match Check 
    if ($parent_password !== $confirm_password) {
        $registration_error = 'Passwords do not match. Please ensure both fields are identical.';
    } 
    
    // Server-side Check for Empty Fields
    if (empty($child_name) || empty($parent_email) || empty($parent_password)) {
        $registration_error = 'Please fill in all required fields.';
    }

    if (empty($registration_error)) {
        // 2. Hash the Password for Security
        $hashed_password = password_hash($parent_password, PASSWORD_DEFAULT);

// In the PHP block of registration.php:
$sql = "INSERT INTO users (child_name, dob, parent_name, username, password_hash, enrollment_type, start_date, notes) 
        VALUES (:child_name, :dob, :parent_name, :username, :password_hash, :enrollment_type, :start_date, :notes)";

// Ensure the bindValue section ALSO only contains these 8 values.
        try {
            // Prepare the statement using the $pdo object
            $stmt = $pdo->prepare($sql);

            // Bind values to named placeholders
            $stmt->bindValue(':child_name', $child_name);
            $stmt->bindValue(':dob', $dob);
            $stmt->bindValue(':parent_name', $parent_name);
            $stmt->bindValue(':username', $parent_email); 
            $stmt->bindValue(':password_hash', $hashed_password); 
            $stmt->bindValue(':enrollment_type', $enrollment_type);
            $stmt->bindValue(':start_date', $start_date);
            $stmt->bindValue(':notes', $notes);
            
            // 4. Execute the statement
            $stmt->execute();
            
            // Registration successful! Redirect (Post/Redirect/Get pattern)
            header('Location: registration.php?status=success');
            exit; 
            
        } catch (\PDOException $e) {
            // Handle unique constraint violation (email already registered)
            if ($e->getCode() === '23000') { 
                 $registration_error = 'This email address is already registered. Please use the login page.';
            } else {
                 $registration_error = 'A critical database error occurred during registration. Please try again.';
                 // For development debugging: $registration_error = 'A critical database error occurred: ' . $e->getMessage();
            }
        }
    }
}

// Check for redirect status to display the success message
if (isset($_GET['status']) && $_GET['status'] == 'success') {
    $success = true;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Online Admission - Humulani Pre School</title> 
    
    <style>
        /* CSS styles for the page */
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes rainbowShine {
            0% { border-color: #ff0000; } 50% { border-color: #0000ff; } 100% { border-color: #ff0000; }
        }
        /* --- Base & Layout --- */
        body { font-family: 'Poppins', sans-serif; margin: 0; padding: 0; background-color: #fcfcfc; text-align: left; }
        .container { max-width: 1100px; margin: 0 auto; padding: 0 20px; }
        h1 {
            color: #ff0077; /* Pink accent for Admission page */
            font-size: 2.8em; margin-top: 30px;
            border-bottom: 3px solid #ff9900; 
            padding-bottom: 10px; margin-bottom: 40px;
        }
        /* --- Navigation Styles --- */
        .site-header { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; background-color: #fff; border-bottom: 3px solid transparent; animation: rainbowShine 8s infinite alternate; }
        .nav-link { text-decoration: none; font-weight: bold; margin: 0 10px; }
        .nav-link:nth-child(1) { color: #ff0000; } .nav-link:nth-child(2) { color: #ff9900; } .nav-link:nth-child(3) { color: #008000; } 
        .nav-link:nth-child(4) { color: #0000ff; } .nav-link:nth-child(5) { color: #4b0082; } .nav-link:nth-child(6) { color: #ee82ee; } 

        /* --- 2. FORM SPECIFIC STYLES --- */
        .admission-form-box {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            max-width: 700px;
            margin: 0 auto 50px auto;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #4b0082; /* Deep violet */
        }
        input[type="text"], input[type="email"], input[type="date"], select, textarea, input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box; /* Ensures padding doesn't affect total width */
        }
        .form-nav-btn {
            background-color: #ff9900; /* Orange */
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s;
            margin-right: 15px;
        }
        .form-nav-btn:hover {
            background-color: #ffb347;
        }
        #step2 {
            display: none; /* Step 2 is hidden initially */
        }
        .success-message {
            padding: 20px;
            background-color: #e6ffe6; /* Light green */
            border: 1px solid #00cc00; /* Darker green border */
            color: #008000;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
        }
        .error-message {
            padding: 10px;
            background-color: #ffe6e6; /* Light red */
            border: 1px solid #cc0000; /* Dark red border */
            color: #cc0000;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        /* Step Indicator Styling */
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 0 10px;
        }
        .step {
            flex: 1;
            text-align: center;
            padding: 10px 0;
            border-bottom: 3px solid #ccc;
            color: #aaa;
            font-weight: bold;
        }
        .step.active {
            border-bottom: 3px solid #ff0077; /* Pink accent */
            color: #ff0077;
        }
        footer { margin-top: 40px; padding: 20px 0; border-top: 1px solid #ddd; text-align: left; }
    </style>
</head>
<body>
    
    <header>
        <div class="container site-header">
            <div style="font-size: 1.5em; font-weight: bold; color: #333;">Humulani Pre School</div>
            
            <nav>
                <a href="index.php" class="nav-link">Home</a>
                <a href="about.php" class="nav-link">About Us</a>
                <a href="gallery.php" class="nav-link">Gallery</a>
                <a href="registration.php" class="nav-link">Admission</a>
                <a href="contact.php" class="nav-link">Contact</a>
                <a href="login.php" class="nav-link">Login</a>
            </nav>
        </div>
    </header>

    <div class="container">
        
        <h1>Online Admission Form</h1> 
        <p style="text-align: center; color: #555;">Begin your child's journey at Humulani in two simple steps.</p>

        <div class="admission-form-box">
            
            <?php if ($success): ?>
                <div class="success-message">
                    🎉 Thank you! Your admission application has been submitted successfully.<br>
                    You can now **<a href="login.php">log in</a>** using your email and the password you provided.
                </div>
            <?php else: ?>
            
                <?php if ($registration_error): ?>
                    <div class="error-message"><?php echo $registration_error; ?></div>
                <?php endif; ?>

                <div class="step-indicator">
                    <span id="indicator1" class="step active">1. Child & Parent Details</span>
                    <span id="indicator2" class="step">2. Enrollment Plan</span>
                </div>

                <form action="registration.php" method="POST" onsubmit="return validateForm()"> 
                    
                    <div id="step1">
                        <h2>Step 1: Child's Information</h2>
                        
                        <label for="child_name">Child's Full Name:</label>
                        <input type="text" id="child_name" name="child_name" required>
                        
                        <label for="dob">Date of Birth:</label>
                        <input type="date" id="dob" name="dob" required>

                        <h2>Parent/Guardian Information</h2>
                        
                        <label for="parent_name">Full Name:</label>
                        <input type="text" id="parent_name" name="parent_name" required>
                        
                        <label for="parent_email">Email Address (This will be your username):</label>
                        <input type="email" id="parent_email" name="parent_email" required>
                        
                        <label for="parent_password">Password (for login):</label>
                        <input type="password" id="parent_password" name="parent_password" required>

                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                        
                        <button type="button" class="form-nav-btn" onclick="nextStep(2)">Next: Enrollment Plan &raquo;</button>
                    </div>

                    <div id="step2">
                        <h2>Step 2: Enrollment & Notes</h2>
                        
                        <label for="enrollment_type">Desired Enrollment Plan:</label>
                        <select id="enrollment_type" name="enrollment_type" required>
                            <option value="">-- Select Plan --</option>
                            <option value="full_day">Full Day (7:00 AM - 5:30 PM)</option>
                            <option value="half_day">Half Day (7:00 AM - 1:00 PM)</option>
                        </select>
                        
                        <label for="start_date">Desired Start Date:</label>
                        <input type="date" id="start_date" name="start_date" required>

                        <label for="notes">Additional Notes / Allergies:</label>
                        <textarea id="notes" name="notes" rows="4"></textarea>

                        <button type="button" class="form-nav-btn" style="background-color: #4b0082;" onclick="nextStep(1)">&laquo; Back</button>
                        <button type="submit" class="form-nav-btn" style="background-color: #008000;">Submit Application</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>

        <footer>
            <p>&copy; 2026 Humulani Pre School</p>
        </footer>
    </div>
    
    <script>
        function nextStep(step) {
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');
            const ind1 = document.getElementById('indicator1');
            const ind2 = document.getElementById('indicator2');

            if (step === 2) {
                const password = document.getElementById('parent_password').value;
                const confirmPassword = document.getElementById('confirm_password').value;

                // Validation for all required fields in Step 1
                if (!document.getElementById('child_name').value || 
                    !document.getElementById('dob').value ||
                    !document.getElementById('parent_name').value ||
                    !document.getElementById('parent_email').value ||
                    !password || 
                    !confirmPassword) {
                    alert("Please fill out all required fields in Step 1 before proceeding.");
                    return false;
                }
                
                // Password Match Check
                if (password !== confirmPassword) {
                    alert("The password and confirmation password do not match. Please try again.");
                    return false;
                }

                // If validation passes, move to next step
                step1.style.display = 'none';
                step2.style.display = 'block';
                ind1.classList.remove('active');
                ind2.classList.add('active');
            } else if (step === 1) {
                step2.style.display = 'none';
                step1.style.display = 'block';
                ind2.classList.remove('active');
                ind1.classList.add('active');
            }
        }
        
        function validateForm() {
            // Check required fields in Step 2 before final submission
            if (!document.getElementById('enrollment_type').value || 
                !document.getElementById('start_date').value) {
                alert("Please select an enrollment plan and start date.");
                return false; 
            }
            return true;
        }

        document.addEventListener('DOMContentLoaded', () => {
             document.getElementById('step1').style.display = 'block';
             document.getElementById('step2').style.display = 'none';
        });
    </script>
</body>
</html>
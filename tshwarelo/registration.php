<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Registration</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<body>
 <?php
  require_once 'menu-bar.php';
 ?>
    <main>
        <form action="#" method="POST" onsubmit="alert('Registration form submitted! (no backend)'); return false;">
            <label for="parentName">Parent/Guardian Name:</label><br />
            <input type="text" id="parentName" name="parentName" required /><br /><br />

            <label for="childName">Child's Name:</label><br />
            <input type="text" id="childName" name="childName" required /><br /><br />

            <label for="childAge">Child's Age:</label><br />
            <input type="number" id="childAge" name="childAge" min="0" max="10" required /><br /><br />

            <label for="contact">Contact Number:</label><br />
            <input type="tel" id="contact" name="contact" pattern="[0-9]{10}" placeholder="e.g., 0123456789" required /><br /><br />

            <label for="email">Email Address:</label><br />
            <input type="email" id="email" name="email" required /><br /><br />

            <label for="medical">Medical Information / Allergies:</label><br />
            <textarea id="medical" name="medical" rows="4" cols="40"></textarea><br /><br />

            <input type="submit" value="Register" />
        </form>
    </main>
      <footer>
        <p>© 2025 Ndlovu's Creche</p>
    </footer>
</body>
</html>


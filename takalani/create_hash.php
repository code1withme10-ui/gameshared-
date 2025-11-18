<?php
// Define the password you want to use for the headmaster
$plainPassword = "Admin123@"; 

// Generate the hash using PHP's built-in password hashing function
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

echo "<h2>Use this new Hashed Password:</h2>";
echo "<p><strong>Plain Password:</strong> " . htmlspecialchars($plainPassword) . "</p>";
echo "<p><strong>Hashed Password:</strong> <code>" . htmlspecialchars($hashedPassword) . "</code></p>";
echo "<p>You can now delete this file ('create_hash.php').</p>";
?>

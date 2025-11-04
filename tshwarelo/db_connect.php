<?php
// Configuration variables matching your docker-compose.yml file
// -------------------------------------------------------------

// 1. CRITICAL FIX: Use the Docker Compose service name 'db' as the hostname
$host = 'db'; 

// 2. Credentials matching the 'db' service in docker-compose.yml
$db   = 'gameshare_db';             
$user = 'gameshare_user';           
$pass = 'supersecurepassword123';   

// Other configuration
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Set PDO options for error handling and fetching
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Essential for debugging and proper error reporting
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     // Attempt to create the PDO connection object
     $pdo = new PDO($dsn, $user, $pass, $options);
     
} catch (\PDOException $e) {
     // If the connection fails (e.g., wrong host, wrong password), 
     // we stop the script and display a clear error message.
     
     // IMPORTANT: In a production environment, you would log the message and display 
     // a generic error. For development, we display the error for debugging.
     
     error_log("Database connection error: " . $e->getMessage()); 
     
     // Stop execution completely so $pdo is not used when it's null/undefined
     die("<h1>Database Connection Failed</h1><p>Check your <code>db_connect.php</code> file and ensure the 'db' service is running in Docker Compose. Error detail: " . $e->getMessage() . "</p>");
}

// If the script reaches this point, the connection is successful, 
// and the $pdo variable is defined and ready to use by registration.php.
?>
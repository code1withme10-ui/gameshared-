-- Ensure the 'users' table is created if it doesn't exist.
-- This script runs automatically when the MySQL container starts up the first time.

-- NOTE: The username column must be unique and is used for login (email).
-- Ensure the column names match what you are using in registration.php (e.g., parent_name, contact_number, child_name, child_age, username, password_hash).

CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    parent_name VARCHAR(100) NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE COMMENT 'Email used for login',
    password_hash VARCHAR(255) NOT NULL,
    contact_number VARCHAR(20),
    child_name VARCHAR(100) NOT NULL,
    child_age INT(2) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
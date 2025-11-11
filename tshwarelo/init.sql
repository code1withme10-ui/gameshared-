CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    
    -- CORE LOGIN/PARENT INFO (NOT NULL, required by form)
    parent_name VARCHAR(100) NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE COMMENT 'Email used for login',
    password_hash VARCHAR(255) NOT NULL,
    
    -- CHILD INFO COLLECTED BY FORM (NOT NULL, required by form)
    child_name VARCHAR(100) NOT NULL,
    dob DATE NOT NULL,
    enrollment_type VARCHAR(50) NOT NULL,
    start_date DATE NOT NULL,
    
    -- NON-ESSENTIAL/MISSING FORM FIELDS (SET TO NULL, not sent by registration.php)
    contact_number VARCHAR(20) NULL,  
    child_age INT(2) NULL,             
    
    -- OPTIONAL INFO
    notes TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
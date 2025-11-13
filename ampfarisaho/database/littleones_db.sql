CREATE DATABASE IF NOT EXISTS littleones_db;
USE littleones_db;

CREATE TABLE parents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    parent_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    parent_id INT,
    parent_name VARCHAR(100),
    child_name VARCHAR(100),
    child_age INT,
    parent_email VARCHAR(100),
    status VARCHAR(50) DEFAULT 'Pending',
    date_registered TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


<?php
// config.php
// Change these values before deploying


// Relative path to CSV file (directory must be writable by PHP)
define('DATA_DIR', __DIR__ . '/data');
define('DATA_FILE', DATA_DIR . '/entries.csv');


// Admin password (change this to a strong password)
define('ADMIN_PASSWORD', 'ChangeThisPass!');


// Basic settings
define('CSV_DELIM', ',');


// If you want to force timezone
date_default_timezone_set('Africa/Johannesburg');

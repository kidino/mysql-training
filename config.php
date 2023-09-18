<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'sakila');
define('DB_PORT', 4408); // -- update accordingly
define('DB_CHARSET', 'utf8');

// Application Configuration
define('DEBUG_MODE', true);
define('BASE_URL', 'http://sakila.test/');
define('APP_ROOT', dirname(__FILE__));

// Error Handling Configuration
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ERROR);
    ini_set('display_errors', 0);
}

session_start();

try {
    // Create a new PDO instance
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";port=" . DB_PORT . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
} catch (PDOException $e) {
    // Handle any database connection or query errors here
    echo "Database Error: " . $e->getMessage();
}

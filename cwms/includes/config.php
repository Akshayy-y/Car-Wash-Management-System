<?php
// Define DB constants only once
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'carwash');
}

// Start session only once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Create PDO connection if not already set
if (!isset($dbh)) {
    try {
        $dbh = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
            DB_USER,
            DB_PASS,
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
        );
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        // Optional: Log actual error to a file (never expose to user)
        error_log("DB Connection error: " . $e->getMessage());

        // Redirect to a custom error page (user-friendly)
        header("Location: ../error.php"); 
        exit();
    }
}
?>

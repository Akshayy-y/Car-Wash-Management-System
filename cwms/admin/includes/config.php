<?php 
// DB credentials for MAMP
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Leave empty if no password set in MAMP
define('DB_NAME', 'carwash');

try {
    // Create PDO connection
    $dbh = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
    );

    // Set error mode to exception
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Log error or show user-friendly message in production
    die("Database connection failed: " . $e->getMessage());
}
?>

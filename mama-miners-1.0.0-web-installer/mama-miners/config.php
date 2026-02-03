<?php
// config.php - DB connection + basic settings
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'mama_miners');
define('SITE_NAME', 'Mama Miners');

function db() {
    static $conn;
    if (!$conn) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
        if ($conn->connect_errno) {
            die("DB connection failed: " . $conn->connect_error);
        }
        // Ensure database exists
        $conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
        $conn->select_db(DB_NAME);
        // set charset
        $conn->set_charset('utf8mb4');
    }
    return $conn;
}

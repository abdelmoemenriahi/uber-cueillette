<?php
// Configure these environment variables for your local MySQL installation.
$servname = getenv('UBER_DB_HOST') ?: '127.0.0.1';
$dbname   = getenv('UBER_DB_NAME') ?: 'uber_cueillette';
$user     = getenv('UBER_DB_USER') ?: 'root';
$pass     = getenv('UBER_DB_PASSWORD') ?: '';

try {
    $pdo = new PDO(
        "mysql:host={$servname};dbname={$dbname};charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    error_log('Uber-Cueillette database connection failed: ' . $e->getMessage());
    http_response_code(500);
    exit('Database connection failed. Check your local database configuration.');
}
?>

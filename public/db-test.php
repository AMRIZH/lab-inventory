<?php
// Simple database connection test
// Access via: /db-test.php

header('Content-Type: application/json');

try {
    $host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'db.icdscorcuiucwipusohb.supabase.co';
    $port = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?? '5432';
    $database = $_ENV['DB_DATABASE'] ?? getenv('DB_DATABASE') ?? 'postgres';
    $username = $_ENV['DB_USERNAME'] ?? getenv('DB_USERNAME') ?? 'postgres';
    $password = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?? '';
    
    $dsn = "pgsql:host={$host};port={$port};dbname={$database}";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_TIMEOUT => 5,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Database connection successful',
        'host' => $host,
        'database' => $database
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database connection failed: ' . $e->getMessage(),
        'host' => $host ?? 'unknown',
        'database' => $database ?? 'unknown'
    ]);
}
?>

<?php
// Simple health check that doesn't require Laravel to be fully loaded
// Place this in public/health.php

header('Content-Type: application/json');

// Basic health check
$health = [
    'status' => 'ok',
    'timestamp' => date('c'),
    'service' => 'Lab Inventory',
    'version' => '1.0.0'
];

// Optional: Check if basic PHP functions work
if (function_exists('json_encode')) {
    $health['php'] = 'ok';
}

// Optional: Check if storage is writable (without Laravel)
if (is_writable('../storage')) {
    $health['storage'] = 'writable';
} else {
    $health['storage'] = 'not_writable';
}

echo json_encode($health);
exit;

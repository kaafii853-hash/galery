<?php

// Force error reporting agar detail pesan error tampil di browser
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set folder storage ke /tmp
$storagePath = '/tmp/storage';

if (!is_dir($storagePath)) {
    mkdir($storagePath . '/framework/views', 0755, true);
    mkdir($storagePath . '/framework/sessions', 0755, true);
    mkdir($storagePath . '/framework/cache', 0755, true);
    mkdir($storagePath . '/logs', 0755, true);
    mkdir($storagePath . '/bootstrap/cache', 0755, true);
}

putenv("APP_STORAGE_PATH={$storagePath}");

// Jalankan index.php bawaan Laravel
require __DIR__ . '/../public/index.php';
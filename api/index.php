<?php

// Arahkan folder storage ke /tmp agar Vercel bisa menulis log/cache
$storagePath = '/tmp/storage';

if (!is_dir($storagePath)) {
    mkdir($storagePath . '/framework/views', 0755, true);
    mkdir($storagePath . '/framework/sessions', 0755, true);
    mkdir($storagePath . '/framework/cache', 0755, true);
    mkdir($storagePath . '/logs', 0755, true);
}

putenv("APP_STORAGE_PATH={$storagePath}");

// Jalankan Laravel
require __DIR__ . '/../public/index.php';
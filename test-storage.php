<?php
// Test file untuk debug storage path

echo "=== STORAGE DEBUG ===\n";

// Load Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

echo "STORAGE_PUBLIC_PATH dari env: " . env('STORAGE_PUBLIC_PATH', 'tidak diset') . "\n";

// Test apakah folder storage_public ada
$storagePath = '/home/sitexamy/storage_public';
echo "Path storage_public exist: " . (is_dir($storagePath) ? 'YES' : 'NO') . "\n";
echo "Path storage_public writable: " . (is_writable($storagePath) ? 'YES' : 'NO') . "\n";

// Test disk config
$disk = env('STORAGE_PUBLIC_PATH') ? 'public_web' : 'public';
echo "Disk yang dipilih: " . $disk . "\n";

try {
    $storage = \Illuminate\Support\Facades\Storage::disk($disk);
    echo "Disk root path: " . $storage->getAdapter()->getPathPrefix() . "\n";
    
    // Test write file
    $testFile = 'test-' . time() . '.txt';
    $storage->put($testFile, 'Test content');
    
    if ($storage->exists($testFile)) {
        echo "✅ Test file berhasil dibuat: " . $testFile . "\n";
        echo "URL file: " . $storage->url($testFile) . "\n";
        
        // Cleanup
        $storage->delete($testFile);
    } else {
        echo "❌ Test file gagal dibuat\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
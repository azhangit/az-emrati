<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking Upload Configuration and Permissions...\n";

// Check Disks
echo "Local Disk Config: " . json_encode(config('filesystems.disks.local')) . "\n";
echo "Default Disk: " . config('filesystems.default') . "\n";

// Check Path Permissions
$path = public_path('uploads/all');
if (!file_exists($path)) {
    echo "Path does not exist: $path\n";
    if (mkdir($path, 0775, true)) {
        echo "Created path: $path\n";
    } else {
        echo "Failed to create path: $path\n";
    }
}

if (is_writable($path)) {
    echo "Path is writable: $path\n";
    // Try writing a test file
    $testFile = $path . '/test_write.txt';
    if (file_put_contents($testFile, 'test')) {
        echo "Successfully wrote test file.\n";
        unlink($testFile);
    } else {
        echo "Failed to write test file.\n";
    }
} else {
    echo "Path is NOT writable: $path. Check permissions.\n";
}

// Check if store() works (simulated)
// Verify if 'local' disk uses public_path
$root = config('filesystems.disks.local.root');
echo "Local Disk Root: $root\n";

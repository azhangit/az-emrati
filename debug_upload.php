<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Filesystem Default: " . config('filesystems.default') . "\n";
echo "Filesystem URL: " . config('filesystems.disks.' . config('filesystems.default') . '.url') . "\n";

if (function_exists('getFileBaseURL')) {
    echo "getFileBaseURL(): " . getFileBaseURL() . "\n";
}

$latest = App\Models\Upload::latest()->first();
if ($latest) {
    echo "Latest Upload ID: " . $latest->id . "\n";
    echo "Latest Upload File Name: '" . $latest->file_name . "'\n";
    echo "Latest Upload Original Name: " . $latest->file_original_name . "\n";
    echo "Latest Upload Type: " . $latest->type . "\n";
    echo "Latest Upload External Link: " . $latest->external_link . "\n";
} else {
    echo "No uploads found.\n";
}

<?php
// live_debug_perms.php (Standalone - No Laravel Dependencies)

echo "<h1>Simple Permission Debugger</h1>";

// Current directory (assuming file is in public_html/ or public/)
$current_dir = __DIR__;
echo "<b>Current Script Directory:</b> " . $current_dir . "<br>";

// Target path: uploads/all
$target_dir = $current_dir . '/uploads/all';
echo "<b>Target Upload Directory:</b> " . $target_dir . "<br>";

// Check if directory exists
if (!file_exists($target_dir)) {
    echo "Directory not found. Attempting to create...<br>";
    // Try to create recursively
    if (@mkdir($target_dir, 0777, true)) {
        echo "<span style='color:green'>Created directory successfully.</span><br>";
    } else {
        $error = error_get_last();
        echo "<span style='color:red'>Failed to create directory. Error: " . ($error['message'] ?? 'Unknown') . "</span><br>";
    }
} else {
    echo "Directory exists.<br>";
}

// Check Writability
echo "<b>Is Writable?</b> ";
if (is_writable($target_dir)) {
    echo "<span style='color:green'>YES</span><br>";
} else {
    echo "<span style='color:red'>NO</span><br>";
}

// Check Owner
$user = function_exists('exec') ? exec('whoami') : 'N/A';
echo "<b>Server User (whoami):</b> " . $user . "<br>";

// Try Writing a File
$test_file = $target_dir . '/test_perm_check.txt';
echo "<b>Attempting to Write Test File...</b><br>";

if (@file_put_contents($test_file, 'Permission Check Successful')) {
    echo "<span style='color:green'>Write Success! File created.</span><br>";
    // Cleanup
    unlink($test_file);
} else {
    $error = error_get_last();
    echo "<span style='color:red'>Write Failed! Error: " . ($error['message'] ?? 'Unknown') . "</span><br>";
}

echo "<hr>";
echo "<h3>Next Steps:</h3>";
echo "If Write Failed, please set permissions of <code>$target_dir</code> to <b>777</b> using your File Manager.";
?>

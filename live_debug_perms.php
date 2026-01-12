<?php
// live_debug_perms.php

echo "<h1>Server Permission Debugger</h1>";

$public_path = public_path();
echo "<b>Public Path:</b> " . $public_path . "<br>";

$upload_path = public_path('uploads/all');
echo "<b>Target Upload Path:</b> " . $upload_path . "<br>";

if (!file_exists($upload_path)) {
    echo "<span style='color:red'>Path does not exist! attempting to create...</span><br>";
    if (mkdir($upload_path, 0777, true)) {
        echo "<span style='color:green'>Created path successfully.</span><br>";
    } else {
        echo "<span style='color:red'>Failed to create path. Check parent permissions.</span><br>";
    }
} else {
    echo "Path exists.<br>";
}

echo "<b>Is Writable?</b> ";
if (is_writable($upload_path)) {
    echo "<span style='color:green'>YES</span><br>";
} else {
    echo "<span style='color:red'>NO</span><br>";
}

echo "<b>Current PHP User:</b> " . exec('whoami') . "<br>";

$test_file = $upload_path . '/test_perm.txt';
echo "<b>Attempting to write file...</b><br>";

if (@file_put_contents($test_file, 'Testing write access...')) {
    echo "<span style='color:green'>Write Success!</span><br>";
    unlink($test_file);
} else {
    $error = error_get_last();
    echo "<span style='color:red'>Write Failed! Error: " . ($error['message'] ?? 'Unknown') . "</span><br>";
}

echo "<hr>";
echo "<h3>Recommendations:</h3>";
echo "If 'Is Writable' is NO, please go to File Manager and set permissions for <code>" . $upload_path . "</code> to <b>777</b>.<br>";

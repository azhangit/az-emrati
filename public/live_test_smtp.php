<?php
// live_test_smtp.php

// 1. Bootstrap Laravel (Adjust path if needed)
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

$to_email = $_GET['email'] ?? null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>SMTP Tester</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        pre { background: #f4f4f4; padding: 10px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <h1>Laravel SMTP Tester</h1>
    
    <h3>Current Configuration (Masked):</h3>
    <ul>
        <li>Driver: <?php echo Config::get('mail.driver'); ?></li>
        <li>Host: <?php echo Config::get('mail.host'); ?></li>
        <li>Port: <?php echo Config::get('mail.port'); ?></li>
        <li>Encryption: <?php echo Config::get('mail.encryption'); ?></li>
        <li>Username: <?php echo Config::get('mail.username') ? 'Set (******)' : 'Not Set'; ?></li>
        <li>From Address: <?php echo Config::get('mail.from.address'); ?></li>
    </ul>

    <hr>

    <form method="GET">
        <label>Send Test Email To:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($to_email ?? ''); ?>" required placeholder="connecttoabdulrehman01@gmail.com">
        <button type="submit">Send Test Email</button>
    </form>

    <?php if ($to_email): ?>
        <hr>
        <h3>Attempting to send...</h3>
        <?php
        try {
            Mail::raw('This is a test email from your Server to verify SMTP settings.', function ($message) use ($to_email) {
                $message->to($to_email)
                        ->subject('SMTP Test - Success');
            });
            echo "<p class='success'>Email sent successfully to $to_email!</p>";
            echo "<p>Please check your inbox (and spam folder).</p>";
        } catch (\Exception $e) {
            echo "<p class='error'>Email sending failed!</p>";
            echo "<strong>Error Message:</strong> " . $e->getMessage() . "<br>";
            echo "<h4>Full Trace:</h4>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
        ?>
    <?php endif; ?>

</body>
</html>

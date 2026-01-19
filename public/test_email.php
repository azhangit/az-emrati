<?php

// Load Laravel's autoloader
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap the Laravel application
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Bootstrap the Console Kernel to load the application environment (config, providers, etc.)
// We use the Console Kernel instead of Http Kernel to avoid routing the request through Laravel's router.
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;

// Get email from query string
$email = $_GET['email'] ?? null;

if (!$email) {
    echo "Please provide an email query parameter.<br>";
    echo "Example Usage: <code>/test_email.php?email=connecttoabdulrehman01@gmail.com</code>";
    exit;
}

try {
    Mail::raw('This is a test email sent from the public/test_email.php script.', function ($message) use ($email) {
        $message->to($email)
            ->subject('Public Folder Test Email');
    });
    echo "Email sent to <strong>$email</strong> successfully!";
} catch (\Throwable $e) {
    echo "Failed to send email: " . $e->getMessage();
}

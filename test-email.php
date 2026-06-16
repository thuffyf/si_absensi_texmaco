<?php

// Test SMTP connection manual
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TESTING SMTP CONNECTION ===\n\n";

// Show current mail config
echo "1. Current Mail Configuration:\n";
echo "   MAIL_MAILER: " . config('mail.default') . "\n";
echo "   MAIL_HOST: " . config('mail.mailers.smtp.host') . "\n";
echo "   MAIL_PORT: " . config('mail.mailers.smtp.port') . "\n";
echo "   MAIL_USERNAME: " . config('mail.mailers.smtp.username') . "\n";
echo "   MAIL_PASSWORD: " . (config('mail.mailers.smtp.password') ? '***SET***' : 'NULL') . "\n";
echo "   MAIL_ENCRYPTION: " . config('mail.mailers.smtp.encryption') . "\n";
echo "   MAIL_FROM_ADDRESS: " . config('mail.from.address') . "\n";
echo "   MAIL_FROM_NAME: " . config('mail.from.name') . "\n\n";

// Test send email
echo "2. Testing email sending...\n";

try {
    Illuminate\Support\Facades\Mail::raw('Test email dari local development', function ($message) {
        $message->to('zulfirman@example.com')
                ->subject('Test SMTP Connection - Sistem Absensi');
    });
    
    echo "   ✅ Email sent successfully!\n";
    echo "   Check your inbox (including spam folder)\n\n";
    
} catch (\Exception $e) {
    echo "   ❌ ERROR: " . $e->getMessage() . "\n\n";
    echo "   Full error:\n";
    echo "   " . $e->getTraceAsString() . "\n\n";
}

echo "3. Checking .env file:\n";
$envPath = __DIR__ . '/.env';
if (file_exists($envPath)) {
    $envContent = file_get_contents($envPath);
    preg_match_all('/MAIL_[A-Z_]+=.*/m', $envContent, $matches);
    foreach ($matches[0] as $line) {
        // Hide password
        if (strpos($line, 'MAIL_PASSWORD') !== false) {
            echo "   MAIL_PASSWORD=***HIDDEN***\n";
        } else {
            echo "   " . $line . "\n";
        }
    }
} else {
    echo "   ⚠️ .env file not found!\n";
}

echo "\n=== TEST COMPLETE ===\n";

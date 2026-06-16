<?php

// Test multiple SMTP configurations
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TESTING MULTIPLE SMTP CONFIGURATIONS ===\n\n";

// Get current password from .env
$currentPassword = config('mail.mailers.smtp.password');

// Test configurations
$configs = [
    [
        'name' => 'Config 1: Port 465 + SSL',
        'host' => 'mail.sitexa.my.id',
        'port' => 465,
        'encryption' => 'ssl',
        'username' => 'absensi@sitexa.my.id',
        'password' => $currentPassword,
    ],
    [
        'name' => 'Config 2: Port 465 + TLS',
        'host' => 'mail.sitexa.my.id',
        'port' => 465,
        'encryption' => 'tls',
        'username' => 'absensi@sitexa.my.id',
        'password' => $currentPassword,
    ],
    [
        'name' => 'Config 3: Port 587 + TLS',
        'host' => 'mail.sitexa.my.id',
        'port' => 587,
        'encryption' => 'tls',
        'username' => 'absensi@sitexa.my.id',
        'password' => $currentPassword,
    ],
    [
        'name' => 'Config 4: Port 587 + STARTTLS',
        'host' => 'mail.sitexa.my.id',
        'port' => 587,
        'encryption' => 'starttls',
        'username' => 'absensi@sitexa.my.id',
        'password' => $currentPassword,
    ],
];

foreach ($configs as $index => $config) {
    echo "Testing " . $config['name'] . "...\n";
    echo "  Host: {$config['host']}\n";
    echo "  Port: {$config['port']}\n";
    echo "  Encryption: {$config['encryption']}\n";
    echo "  Username: {$config['username']}\n";
    echo "  Password: " . (empty($config['password']) ? 'EMPTY' : '***SET***') . "\n";
    
    try {
        // Create transport
        $transport = new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport(
            $config['host'],
            $config['port'],
            $config['encryption'] === 'ssl' || $config['encryption'] === 'tls'
        );
        
        $transport->setUsername($config['username']);
        $transport->setPassword($config['password']);
        
        // Create mailer
        $mailer = new \Symfony\Component\Mailer\Mailer($transport);
        
        // Create email
        $email = (new \Symfony\Component\Mime\Email())
            ->from($config['username'])
            ->to('test@example.com')
            ->subject('Test SMTP - ' . $config['name'])
            ->text('This is a test email');
        
        // Try to send
        $mailer->send($email);
        
        echo "  ✅ SUCCESS! This configuration works!\n";
        echo "  \n";
        echo "  >>> USE THIS IN .ENV:\n";
        echo "  MAIL_HOST={$config['host']}\n";
        echo "  MAIL_PORT={$config['port']}\n";
        echo "  MAIL_ENCRYPTION={$config['encryption']}\n";
        echo "  MAIL_USERNAME={$config['username']}\n";
        echo "  MAIL_PASSWORD=[your-password]\n";
        echo "  \n";
        break; // Stop after first success
        
    } catch (\Exception $e) {
        echo "  ❌ FAILED: " . $e->getMessage() . "\n";
    }
    
    echo "\n" . str_repeat('-', 70) . "\n\n";
}

echo "\n=== RECOMMENDATIONS ===\n\n";
echo "If all configurations failed, check:\n";
echo "1. Password is correct (try login to webmail)\n";
echo "2. Email account 'absensi@sitexa.my.id' exists\n";
echo "3. SMTP authentication is enabled\n";
echo "4. Firewall is not blocking ports 465/587\n";
echo "5. Your IP is not blocked by server\n\n";

echo "To verify password, try:\n";
echo "1. Login to webmail: https://sitexa.my.id:2096\n";
echo "2. Use credentials: absensi@sitexa.my.id + [password]\n";
echo "3. If login fails, password is wrong!\n\n";

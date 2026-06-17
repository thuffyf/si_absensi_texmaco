<?php
// Upload file ini ke server production dan jalankan via browser atau SSH

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Test SMTP dari Server Production</h2>";
echo "<pre>";

// Test basic connection
echo "1. Testing telnet to mail.sitexa.my.id:465\n";
$fp = @fsockopen('mail.sitexa.my.id', 465, $errno, $errstr, 10);
if ($fp) {
    echo "   ✅ Port 465 accessible\n";
    fclose($fp);
} else {
    echo "   ❌ Port 465 NOT accessible: $errstr ($errno)\n";
}

echo "\n2. Testing telnet to mail.sitexa.my.id:587\n";
$fp = @fsockopen('mail.sitexa.my.id', 587, $errno, $errstr, 10);
if ($fp) {
    echo "   ✅ Port 587 accessible\n";
    fclose($fp);
} else {
    echo "   ❌ Port 587 NOT accessible: $errstr ($errno)\n";
}

// Test with PHPMailer (simple test without Laravel)
echo "\n3. Testing email send with mail() function\n";

$to = "test@example.com";
$subject = "Test dari " . $_SERVER['HTTP_HOST'];
$message = "Email test berhasil dari server production!";
$headers = "From: absensi@sitexa.my.id\r\n";
$headers .= "Reply-To: absensi@sitexa.my.id\r\n";

if (mail($to, $subject, $message, $headers)) {
    echo "   ✅ mail() function works!\n";
} else {
    echo "   ❌ mail() function failed\n";
}

echo "\n4. Server Information\n";
echo "   Hostname: " . gethostname() . "\n";
echo "   Server IP: " . $_SERVER['SERVER_ADDR'] . "\n";
echo "   PHP Version: " . PHP_VERSION . "\n";

echo "</pre>";
echo "<hr>";
echo "<p><strong>NEXT STEP:</strong></p>";
echo "<ul>";
echo "<li>If port 465/587 accessible → password issue</li>";
echo "<li>If ports NOT accessible → hosting block SMTP</li>";
echo "<li>Contact hosting support if needed</li>";
echo "</ul>";

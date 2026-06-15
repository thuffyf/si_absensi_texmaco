<?php
header('Content-Type: text/plain');

echo "=== NFC API Test ===\n";
echo "Time: " . date('Y-m-d H:i:s') . "\n";
echo "Timezone: " . date_default_timezone_get() . "\n\n";

// Test database connection
$env_file = __DIR__ . '/../.env';
$db_config = [
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '',
    'name' => 'si_absensi_texmaco'
];

if (file_exists($env_file)) {
    echo ".env file: FOUND\n";
    $env_content = file_get_contents($env_file);
    $env_lines = explode("\n", $env_content);
    
    foreach ($env_lines as $line) {
        $line = trim($line);
        
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        
        if (strpos($line, 'DB_HOST=') === 0) {
            $db_config['host'] = trim(str_replace('DB_HOST=', '', $line));
        } elseif (strpos($line, 'DB_USERNAME=') === 0) {
            $db_config['user'] = trim(str_replace('DB_USERNAME=', '', $line));
        } elseif (strpos($line, 'DB_PASSWORD=') === 0) {
            $db_config['pass'] = trim(str_replace('DB_PASSWORD=', '', $line));
        } elseif (strpos($line, 'DB_DATABASE=') === 0) {
            $db_config['name'] = trim(str_replace('DB_DATABASE=', '', $line));
        }
    }
} else {
    echo ".env file: NOT FOUND\n";
}

echo "\nDatabase Config:\n";
echo "- Host: " . $db_config['host'] . "\n";
echo "- User: " . $db_config['user'] . "\n";
echo "- Pass: " . (empty($db_config['pass']) ? '(empty)' : '***') . "\n";
echo "- Database: " . $db_config['name'] . "\n\n";

// Test connection
$conn = new mysqli(
    $db_config['host'], 
    $db_config['user'], 
    $db_config['pass'], 
    $db_config['name']
);

if ($conn->connect_error) {
    echo "Database: CONNECTION FAILED\n";
    echo "Error: " . $conn->connect_error . "\n";
} else {
    echo "Database: CONNECTED ✓\n\n";
    
    // Test tables
    $tables = ['students', 'attendances', 'scan_attempts', 'nfc_devices'];
    echo "Checking tables:\n";
    foreach ($tables as $table) {
        $result = $conn->query("SELECT COUNT(*) as count FROM $table");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "- $table: " . $row['count'] . " rows ✓\n";
        } else {
            echo "- $table: ERROR - " . $conn->error . "\n";
        }
    }
    
    $conn->close();
}

echo "\n=== Test Complete ===\n";

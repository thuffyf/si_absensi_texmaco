<?php
// Enable error logging untuk debugging
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/nfc_api_error.log');

// Catch all errors
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    error_log("NFC API Error [$errno]: $errstr in $errfile on line $errline");
    echo "PHP_ERROR";
    exit;
});

header('Content-Type: text/plain');

// Load database config dari Laravel .env
// Coba baca dari parent directory (.env Laravel)
$env_file = __DIR__ . '/../.env';
$db_config = [
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '',
    'name' => 'si_absensi_texmaco'
];

if (file_exists($env_file)) {
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
}

// Koneksi database
$conn = new mysqli(
    $db_config['host'], 
    $db_config['user'], 
    $db_config['pass'], 
    $db_config['name']
);

if ($conn->connect_error) {
    // Log error untuk debugging
    error_log("NFC API DB Connection Error: " . $conn->connect_error);
    echo "DB_ERROR";
    exit;
}

// Set timezone ke Asia/Jakarta
date_default_timezone_set('Asia/Jakarta');

// Terima data dari ESP32
$uid_raw = isset($_POST['uid']) ? $_POST['uid'] : '';
$device_id = isset($_POST['id_alat']) ? $_POST['id_alat'] : 1;

if ($uid_raw == "") {
    echo "NO_UID";
    exit;
}

// ========== BERSIHKAN UID ==========
$uid_clean = strtoupper(str_replace(" ", "", $uid_raw));

// ========== CEK APAKAH DEVICE ID VALID ==========
$check_device = "SELECT id FROM nfc_devices WHERE id = $device_id";
$device_result = $conn->query($check_device);
$valid_device_id = ($device_result && $device_result->num_rows > 0) ? $device_id : "NULL";

// ========== CARI SISWA ==========
$sql = "SELECT * FROM students WHERE REPLACE(UPPER(uid_kartu), ' ', '') = '$uid_clean'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    // Log unregistered card
    $waktu = date('Y-m-d H:i:s');
    $log_sql = "INSERT INTO scan_attempts (uid_kartu, device_id, status, response_message, scanned_at, created_at, updated_at) VALUES ('$uid_clean', $valid_device_id, 'unregistered', 'KARTU TIDAK TERDAFTAR', '$waktu', NOW(), NOW())";
    $conn->query($log_sql);
    
    echo "KARTU TIDAK TERDAFTAR";
    $conn->close();
    exit;
}

$siswa = $result->fetch_assoc();
$student_id = $siswa['id'];
$tanggal = date('Y-m-d');
$waktu = date('H:i:s');

// Cek sudah absen hari ini
$cek = "SELECT * FROM attendances WHERE student_id = $student_id AND attendance_date = '$tanggal'";
$cek_result = $conn->query($cek);

if ($cek_result->num_rows > 0) {
    // Log already attended
    $waktu_log = date('Y-m-d H:i:s');
    $log_sql = "INSERT INTO scan_attempts (uid_kartu, device_id, status, response_message, scanned_at, created_at, updated_at) VALUES ('$uid_clean', $valid_device_id, 'already_attended', 'SUDAH ABSEN', '$waktu_log', NOW(), NOW())";
    $conn->query($log_sql);
    
    echo "SUDAH ABSEN";
    $conn->close();
    exit;
}

// Simpan absen dengan device_id
$insert = "INSERT INTO attendances (student_id, device_id, attendance_date, attendance_time, status, created_at, updated_at) 
VALUES ($student_id, $valid_device_id, '$tanggal', '$waktu', 'hadir', NOW(), NOW())";

if ($conn->query($insert)) {
    // Log successful scan
    $waktu_log = date('Y-m-d H:i:s');
    $log_sql = "INSERT INTO scan_attempts (uid_kartu, device_id, status, response_message, scanned_at, created_at, updated_at) VALUES ('$uid_clean', $valid_device_id, 'success', 'BERHASIL', '$waktu_log', NOW(), NOW())";
    $conn->query($log_sql);
    
    // Update device scan count (hanya jika device valid)
    if ($valid_device_id != "NULL") {
        $update_device = "UPDATE nfc_devices SET scan_today = scan_today + 1, last_scan_at = NOW(), last_seen_at = NOW() WHERE id = $device_id";
        $conn->query($update_device);
    }
    
    echo "BERHASIL";
} else {
    // Log error untuk debugging
    $error_msg = $conn->error;
    error_log("NFC API Insert Error: " . $error_msg . " | SQL: " . $insert);
    
    // Coba log error ke scan_attempts juga
    $waktu_log = date('Y-m-d H:i:s');
    $error_clean = $conn->real_escape_string($error_msg);
    $log_sql = "INSERT INTO scan_attempts (uid_kartu, device_id, status, response_message, scanned_at, created_at, updated_at) VALUES ('$uid_clean', $valid_device_id, 'error', 'ERROR: $error_clean', '$waktu_log', NOW(), NOW())";
    $conn->query($log_sql);
    
    echo "GAGAL_SIMPAN";
}

$conn->close();
?>
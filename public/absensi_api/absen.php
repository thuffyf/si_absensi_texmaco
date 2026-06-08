<?php
header('Content-Type: text/plain');

// Koneksi database
$host = "127.0.0.1";
$user = "sitexamy_sitexatu";
$pass = "admintutexmaco123";
$db   = "sitexamy_si_absensi_texmaco";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
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

// BERSIHKAN UID
global $uid_clean;
$uid_clean = strtoupper(str_replace(" ", "", $uid_raw));

// CEK APAKAH DEVICE ID VALID
$check_device = "SELECT id FROM nfc_devices WHERE id = $device_id";
$device_result = $conn->query($check_device);
$valid_device_id = ($device_result && $device_result->num_rows > 0) ? $device_id : "NULL";

// CARI SISWA
$sql = "SELECT * FROM students WHERE REPLACE(UPPER(uid_kartu), ' ', '') = '$uid_clean'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
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
    $waktu_log = date('Y-m-d H:i:s');
    $log_sql = "INSERT INTO scan_attempts (uid_kartu, device_id, status, response_message, scanned_at, created_at, updated_at) VALUES ('$uid_clean', $valid_device_id, 'already_attended', 'SUDAH ABSEN', '$waktu_log', NOW(), NOW())";
    $conn->query($log_sql);
    echo "SUDAH ABSEN";
    $conn->close();
    exit;
}

$insert = "INSERT INTO attendances (student_id, attendance_date, attendance_time, status, created_at, updated_at) VALUES ($student_id, '$tanggal', '$waktu', 'hadir', NOW(), NOW())";

if ($conn->query($insert)) {
    $waktu_log = date('Y-m-d H:i:s');
    $log_sql = "INSERT INTO scan_attempts (uid_kartu, device_id, status, response_message, scanned_at, created_at, updated_at) VALUES ('$uid_clean', $valid_device_id, 'success', 'BERHASIL', '$waktu_log', NOW(), NOW())";
    $conn->query($log_sql);
    if ($valid_device_id != "NULL") {
        $update_device = "UPDATE nfc_devices SET scan_today = scan_today + 1, last_scan_at = NOW(), last_seen_at = NOW() WHERE id = $device_id";
        $conn->query($update_device);
    }
    echo "BERHASIL";
} else {
    echo "GAGAL_SIMPAN";
}

$conn->close();
?>
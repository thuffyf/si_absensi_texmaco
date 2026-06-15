<?php
// Versi SAFE dengan error handling lengkap
ini_set('display_errors', 0);
error_reporting(E_ALL);

header('Content-Type: text/plain');

try {
    // Load database config
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

    // Set timezone
    date_default_timezone_set('Asia/Jakarta');

    // Koneksi database
    $conn = @new mysqli(
        $db_config['host'], 
        $db_config['user'], 
        $db_config['pass'], 
        $db_config['name']
    );

    if ($conn->connect_error) {
        throw new Exception("DB_ERROR: " . $conn->connect_error);
    }

    // Terima data
    $uid_raw = isset($_POST['uid']) ? $_POST['uid'] : '';
    $device_id = isset($_POST['id_alat']) ? (int)$_POST['id_alat'] : 1;

    if (empty($uid_raw)) {
        throw new Exception("NO_UID");
    }

    // Bersihkan UID
    $uid_clean = strtoupper(str_replace(" ", "", $uid_raw));

    // Cek device
    $stmt = $conn->prepare("SELECT id FROM nfc_devices WHERE id = ?");
    if (!$stmt) {
        throw new Exception("PREPARE_ERROR: " . $conn->error);
    }
    
    $stmt->bind_param("i", $device_id);
    $stmt->execute();
    $device_result = $stmt->get_result();
    $valid_device_id = ($device_result->num_rows > 0) ? $device_id : null;
    $stmt->close();

    // Cari siswa
    $stmt = $conn->prepare("SELECT id FROM students WHERE REPLACE(UPPER(uid_kartu), ' ', '') = ?");
    if (!$stmt) {
        throw new Exception("PREPARE_ERROR: " . $conn->error);
    }
    
    $stmt->bind_param("s", $uid_clean);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Log unregistered
        $waktu = date('Y-m-d H:i:s');
        $log_stmt = $conn->prepare("INSERT INTO scan_attempts (uid_kartu, device_id, status, response_message, scanned_at, created_at, updated_at) VALUES (?, ?, 'unregistered', 'KARTU TIDAK TERDAFTAR', ?, NOW(), NOW())");
        
        if ($log_stmt) {
            $log_stmt->bind_param("sis", $uid_clean, $valid_device_id, $waktu);
            $log_stmt->execute();
            $log_stmt->close();
        }
        
        $stmt->close();
        $conn->close();
        echo "KARTU TIDAK TERDAFTAR";
        exit;
    }

    $siswa = $result->fetch_assoc();
    $student_id = $siswa['id'];
    $stmt->close();

    $tanggal = date('Y-m-d');
    $waktu = date('H:i:s');

    // Cek sudah absen
    $stmt = $conn->prepare("SELECT id FROM attendances WHERE student_id = ? AND attendance_date = ?");
    if (!$stmt) {
        throw new Exception("PREPARE_ERROR: " . $conn->error);
    }
    
    $stmt->bind_param("is", $student_id, $tanggal);
    $stmt->execute();
    $cek_result = $stmt->get_result();

    if ($cek_result->num_rows > 0) {
        // Log already attended
        $waktu_log = date('Y-m-d H:i:s');
        $log_stmt = $conn->prepare("INSERT INTO scan_attempts (uid_kartu, device_id, status, response_message, scanned_at, created_at, updated_at) VALUES (?, ?, 'already_attended', 'SUDAH ABSEN', ?, NOW(), NOW())");
        
        if ($log_stmt) {
            $log_stmt->bind_param("sis", $uid_clean, $valid_device_id, $waktu_log);
            $log_stmt->execute();
            $log_stmt->close();
        }
        
        $stmt->close();
        $conn->close();
        echo "SUDAH ABSEN";
        exit;
    }
    $stmt->close();

    // Simpan absen
    $stmt = $conn->prepare("INSERT INTO attendances (student_id, attendance_date, attendance_time, status, created_at, updated_at) VALUES (?, ?, ?, 'hadir', NOW(), NOW())");
    if (!$stmt) {
        throw new Exception("PREPARE_ERROR: " . $conn->error);
    }
    
    $stmt->bind_param("iss", $student_id, $tanggal, $waktu);
    
    if ($stmt->execute()) {
        $stmt->close();
        
        // Log success
        $waktu_log = date('Y-m-d H:i:s');
        $log_stmt = $conn->prepare("INSERT INTO scan_attempts (uid_kartu, device_id, status, response_message, scanned_at, created_at, updated_at) VALUES (?, ?, 'success', 'BERHASIL', ?, NOW(), NOW())");
        
        if ($log_stmt) {
            $log_stmt->bind_param("sis", $uid_clean, $valid_device_id, $waktu_log);
            $log_stmt->execute();
            $log_stmt->close();
        }
        
        // Update device
        if ($valid_device_id !== null) {
            $update_stmt = $conn->prepare("UPDATE nfc_devices SET scan_today = scan_today + 1, last_scan_at = NOW(), last_seen_at = NOW() WHERE id = ?");
            if ($update_stmt) {
                $update_stmt->bind_param("i", $device_id);
                $update_stmt->execute();
                $update_stmt->close();
            }
        }
        
        echo "BERHASIL";
    } else {
        throw new Exception("INSERT_ERROR: " . $stmt->error);
    }

    $conn->close();

} catch (Exception $e) {
    // Log ke file
    $log_file = __DIR__ . '/nfc_api_error.log';
    $log_msg = date('Y-m-d H:i:s') . " | " . $e->getMessage() . "\n";
    @file_put_contents($log_file, $log_msg, FILE_APPEND);
    
    // Return error yang aman
    $msg = $e->getMessage();
    if (strpos($msg, 'NO_UID') !== false) {
        echo "NO_UID";
    } elseif (strpos($msg, 'DB_ERROR') !== false) {
        echo "DB_ERROR";
    } else {
        echo "ERROR";
    }
}

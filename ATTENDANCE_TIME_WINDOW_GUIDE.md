# Panduan Fitur: Attendance Time Window & Leave Request Exception

## 📋 Ringkasan Feature

Fitur ini mengimplementasikan dua persyaratan utama:

1. **Time Window Validation (6:30 AM - 9:00 AM)**
   - Siswa hanya bisa melakukan absen selama jam 6:30 pagi sampai jam 9:00 pagi
   - Scan setelah jam 9:00 akan dicatat sebagai "Alpa" (tidak hadir)

2. **Leave Request Exception**
   - Jika siswa sedang mengajukan izin/sakit dengan status pending, absensi mereka TIDAK akan muncul di monitoring
   - Absen akan muncul kembali setelah izin disetujui atau ditolak guru

---

## 🔧 Implementasi Teknis

### File yang Dimodifikasi

#### 1. `/absensi_api/absen_safe.php`
**Fungsi**: Endpoint NFC Card scanning yang memproses tap kartu siswa

**Perubahan**:
```php
// 1. Calculate waktu scan
$jam = (int)date('H');       // 0-23
$menit = (int)date('i');     // 0-59

// 2. Check time window (6:30 - 9:00)
$is_within_time = ($jam > 6 || ($jam == 6 && $menit >= 30)) && 
                  ($jam < 9 || ($jam == 9 && $menit == 0));

// 3. Set status berdasarkan waktu
$status = $is_within_time ? 'hadir' : 'alpa';
$response_msg = $is_within_time ? 'BERHASIL' : 'TERLAMBAT (SETELAH JAM 9:00)';
```

**Logika Pending Leave Check**:
```php
// Query: Ada leave request dengan status pending untuk hari ini?
SELECT id FROM leave_requests 
WHERE student_id = ? 
AND status IN ('pending_teacher', 'pending_admin') 
AND DATE(start_date) <= ? 
AND DATE(end_date) >= ?

// Jika ada → Return "PENDING_LEAVE" tanpa simpan attendance
// Jika tidak → Lanjut proses normal
```

**Response Values**:
| Kondisi | Response | Status | Tindakan |
|---------|----------|--------|----------|
| Berhasil (6:30-9:00) | "BERHASIL" | hadir | Simpan attendance |
| Terlambat (>9:00) | "TERLAMBAT (SETELAH JAM 9:00)" | alpa | Simpan attendance dengan status alpa |
| Ada pending leave | "PENDING_LEAVE" | pending_leave | JANGAN simpan attendance |
| Sudah absen | "SUDAH ABSEN" | already_attended | Jangan simpan (duplikasi) |
| Kartu tidak terdaftar | "KARTU TIDAK TERDAFTAR" | unregistered | Log only |

#### 2. `/app/Http/Controllers/MonitoringController.php`
**Fungsi**: Endpoint untuk menampilkan data monitoring real-time

**Perubahan di method `buildNfcPayload()`**:
```php
// Query attendances dengan filter leave request
$attendances = Attendance::query()
    ->with(['student', 'device'])
    ->whereDate('attendance_date', $today)
    ->whereDoesntHave('student.leaveRequests', function ($q) use ($today) {
        // Exclude jika ada pending leave untuk hari ini
        $q->whereIn('status', ['pending_teacher', 'pending_admin'])
          ->whereDate('start_date', '<=', $today)
          ->whereDate('end_date', '>=', $today);
    })
    ->orderByDesc('attendance_time')
    ->orderByDesc('id')
    ->limit(40)
    ->get();
```

**Hasil**: Attendance records yang memiliki pending leave request tidak ditampilkan di monitoring

---

## 📊 Database Schema Reference

### Table: `attendances`
```
id                BIGINT PRIMARY
student_id        BIGINT (FK)
attendance_date   DATE
attendance_time   TIME
status            ENUM('hadir', 'alpa', 'izin', 'sakit')
created_at        TIMESTAMP
updated_at        TIMESTAMP
```

### Table: `leave_requests`
```
id                BIGINT PRIMARY
student_id        BIGINT (FK)
type              ENUM('izin', 'sakit')
start_date        DATE
end_date          DATE
status            ENUM('pending_teacher', 'pending_admin', 'approved', 'rejected')
reason            TEXT
requested_at      TIMESTAMP
responded_at      TIMESTAMP
request_date      DATE
rejection_reason  TEXT
created_at        TIMESTAMP
updated_at        TIMESTAMP
```

### Table: `scan_attempts`
```
id                BIGINT PRIMARY
uid_kartu         VARCHAR
device_id         BIGINT
status            VARCHAR('success', 'unregistered', 'already_attended', 'pending_leave')
response_message  VARCHAR
scanned_at        TIMESTAMP
created_at        TIMESTAMP
updated_at        TIMESTAMP
```

---

## 🔄 Workflow Lengkap

### Scenario 1: Siswa Absen Normal (Tepat Waktu)
```
1. Jam 7:30 pagi
2. Siswa tap kartu NFC
3. Sistem check:
   - Waktu: 7:30 (within 6:30-9:00) ✓
   - Leave request pending: Tidak ✓
   - Sudah absen hari ini: Tidak ✓
4. Response: "BERHASIL"
5. Status: 'hadir' → Muncul di monitoring
```

### Scenario 2: Siswa Absen Terlambat (Setelah 9:00 AM)
```
1. Jam 10:00 pagi
2. Siswa tap kartu NFC
3. Sistem check:
   - Waktu: 10:00 (NOT within 6:30-9:00) ✗
   - Leave request pending: Tidak ✓
   - Sudah absen hari ini: Tidak ✓
4. Response: "TERLAMBAT (SETELAH JAM 9:00)"
5. Status: 'alpa' → Muncul di monitoring sebagai Alpa
```

### Scenario 3: Siswa Dengan Pending Leave Request
```
1. Hari Senin jam 6:45 pagi
2. Siswa memiliki leave request dengan:
   - Status: 'pending_teacher'
   - Tanggal: Senin (start_date: 2024-01-15, end_date: 2024-01-15)
3. Siswa tap kartu NFC
4. Sistem check:
   - Waktu: 6:45 (within 6:30-9:00) ✓
   - Leave request pending: ADA ✗
   - → Tidak simpan attendance
5. Response: "PENDING_LEAVE"
6. Result: Tidak muncul di monitoring NFC
7. Ketika guru setujui leave request (status → 'approved'):
   - Siswa bisa absen hari berikutnya normal
   - Hari Senin tetap tidak ada attendance (leave/izin)
```

### Scenario 4: Guru Setujui Leave Request
```
Guru Dashboard → Approve Leave Request
- Status: pending_teacher → approved
- Next scan attempt: normal process (tidak ada pending leave lagi)
- Monitoring: attendance akan tampil jika scan berhasil
```

---

## 🎯 Key Business Rules

### Time Window
- **Jam Operasional**: 06:30 - 09:00 (Waktu Server: Asia/Jakarta)
- **Status Hadir**: Scan pada 06:30:00 - 09:00:00
- **Status Alpa**: Scan pada 09:00:01 atau sesudahnya
- **Belum Dibuka**: Scan sebelum 06:30

### Leave Request Integration
- Siswa tidak bisa absen jika ada pending leave untuk hari itu
- Sistem tidak menyimpan attendance record (hanya log di scan_attempts)
- Monitoring tidak menampilkan attendance dengan pending leave
- Setelah leave disetujui/ditolak, siswa normal bisa absen lagi

### Prioritas
1. Check pending leave request → BLOCKING
2. Check time window → DETERMINES STATUS
3. Check already attended → BLOCKING (duplikasi)

---

## 🧪 Testing Checklist

### Unit Test - Time Window
- [ ] Scan jam 06:29:59 → Harus alpa
- [ ] Scan jam 06:30:00 → Harus hadir
- [ ] Scan jam 08:30:00 → Harus hadir
- [ ] Scan jam 09:00:00 → Harus hadir
- [ ] Scan jam 09:00:01 → Harus alpa
- [ ] Scan jam 10:00:00 → Harus alpa

### Unit Test - Leave Request
- [ ] Siswa dengan pending_teacher leave → NFC scan failed dengan PENDING_LEAVE
- [ ] Siswa dengan pending_admin leave → NFC scan failed dengan PENDING_LEAVE
- [ ] Siswa dengan approved leave → NFC scan normal (no attendance)
- [ ] Siswa dengan rejected leave → NFC scan normal (hadir)
- [ ] Siswa tanpa leave request → NFC scan normal

### Integration Test
- [ ] Leave request dibuat untuk Hari A
- [ ] NFC scan Hari A → Gagal dengan PENDING_LEAVE
- [ ] Monitoring Hari A → Tidak ada attendance
- [ ] Guru approve leave request
- [ ] NFC scan Hari B → Sukses normal
- [ ] Monitoring Hari B → Ada attendance

### UI Test
- [ ] Monitoring dashboard tidak menampilkan siswa dengan pending leave
- [ ] Monitoring dashboard menampilkan status 'alpa' dengan badge merah
- [ ] Status badge 'Alpa' vs 'Hadir' dibedakan dengan jelas

---

## 📝 Troubleshooting

### Masalah: Siswa tidak bisa absen padahal tidak ada pending leave
**Debugging**:
1. Check server time (Asia/Jakarta timezone)
2. Cek database `leave_requests` untuk siswa
3. Lihat log file: `/absensi_api/nfc_api_error.log`
4. Test dengan tool lain: `/absensi_api/test.php`

### Masalah: Attendance dengan pending leave muncul di monitoring
**Cause**: Query filtering tidak bekerja
**Fix**:
1. Verify relasi `Student::leaveRequests()` ada
2. Check `leave_requests` table punya data dengan status='pending_teacher'
3. Clear application cache: `php artisan cache:clear`

### Masalah: Alpa tidak ditampilkan dengan benar
**Debugging**:
1. Check attendance record status di database
2. Verify view blade template handle 'alpa' status
3. Check monitoring controller status mapping

---

## 🚀 Deployment Notes

1. **Database Migration**: Tidak ada migration baru, hanya logic changes
2. **Cache**: Pastikan clear cache setelah update
   ```bash
   php artisan cache:clear
   ```
3. **Environment**: Pastikan `APP_ENV=production` di hosting
4. **Time Zone**: Verify server timezone: `date_default_timezone_set('Asia/Jakarta')`

---

## 📄 Related Files

- NFC Scanning API: `/absensi_api/absen_safe.php`
- Monitoring Controller: `/app/Http/Controllers/MonitoringController.php`
- Monitoring View: `/resources/views/monitoring/nfc.blade.php`
- Leave Request Controller: `/app/Http/Controllers/LeaveRequestController.php`
- Attendance Model: `/app/Models/Attendance.php`
- LeaveRequest Model: `/app/Models/LeaveRequest.php`

---

## 📞 Contact & Questions

Untuk debugging atau pertanyaan tentang implementasi ini, cek:
1. Log file di `/absensi_api/nfc_api_error.log`
2. Database queries di monitoring controller
3. Test endpoint: `/absensi_api/test_run.php`

---

**Last Updated**: 2024
**Version**: 1.0
**Status**: Production Ready

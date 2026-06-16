# Konteks Project: Sistem Absensi NFC Texmaco

## Overview Project

Ini adalah **Sistem Informasi Absensi berbasis NFC** untuk SMK Texmaco yang dibangun menggunakan **Laravel 10** dan **PHP 8.1+**. Sistem ini mengelola presensi siswa dan guru menggunakan kartu NFC, dilengkapi dengan portal web untuk admin, guru, dan siswa, serta REST API untuk aplikasi mobile.

### Tujuan Utama
- Mencatat kehadiran siswa secara otomatis menggunakan NFC tap
- Mengelola permohonan izin/sakit dengan workflow persetujuan bertingkat (Guru → Tata Usaha)
- Menyediakan dashboard monitoring real-time
- Menghasilkan laporan absensi dengan export CSV/PDF
- Sinkronisasi data dengan sistem eksternal melalui API

## Stack Teknologi

### Backend
- **Framework**: Laravel 10.x
- **PHP Version**: ^8.1
- **Database**: MySQL
- **Authentication**: Laravel Sanctum (untuk API token)
- **PDF Generator**: mPDF ^8.3
- **HTTP Client**: Guzzle ^7.2

### Frontend
- **CSS Framework**: Tailwind CSS ^3.4.19
- **Build Tool**: Vite ^8.0.16
- **HTTP Client**: Axios ^1.6.4
- **Icons**: Custom SVG + Tailwind classes

### API & Integration
- **REST API**: Laravel API Routes
- **External API**: Sinkronisasi ke `http://localhost/absensi_api/absen.php`
- **NFC Authentication**: Custom middleware dengan API key
- **reCAPTCHA**: Google reCAPTCHA v2 (dengan bypass untuk localhost)

## Struktur Database Utama

### Users (Administrator & Portal Access)
- Roles: `admin`, `tata_usaha`, `guru`, `siswa`
- Login untuk admin: email + password (hashed)
- Login untuk siswa/guru: email/NIP + tanggal lahir (YYYY-MM-DD)

### Students (Data Siswa)
- NIS, nama, email, username, password, tanggal lahir
- **UID Kartu NFC** (wajib untuk tap in)
- Kelas: X TEI, XI TEI, XII TEI
- Jurusan: Teknik Elektronika Industri
- Status: aktif/nonaktif
- Photo path, phone
- Relasi: attendances, leaveRequests, devices

### Teachers (Data Guru)
- NIP, nama, email, password, tanggal lahir
- Role, subject, phone, status
- Photo path
- Relasi: schedules

### Attendances (Data Kehadiran)
- Student ID, Device ID, Schedule ID
- Tanggal & waktu absen
- **Status**: `hadir`, `izin`, `sakit`, `alpa`
- Note (catatan tambahan)
- Relasi: student, device, schedule

### LeaveRequests (Permohonan Izin/Sakit)
- Student ID, type (`izin`/`sakit`), reason
- Request date, photo (bukti)
- **Workflow status**:
  - `pending_teacher` → menunggu persetujuan Guru
  - `pending_admin` → menunggu persetujuan Tata Usaha
  - `approved` → disetujui (otomatis create Attendance)
  - `rejected` → ditolak
- Timestamps: teacher_approved_at, admin_approved_at

### Schedules (Jadwal Kelas)
- Teacher ID, kelas, nama mata pelajaran
- Hari, waktu mulai, waktu selesai, ruangan
- Relasi: teacher, attendances

### NfcDevices (Perangkat NFC)
- Nama device, lokasi, status (active/inactive)
- Last sync timestamp
- Relasi: attendances

### Settings (Pengaturan Sistem)
- Key-value pairs untuk konfigurasi
- Example: `notification_izin_sakit_enabled`, `auto_alpha_enabled`

## Alur Kerja Sistem

### 1. Absensi Otomatis (NFC Tap)
```
Siswa tap kartu NFC → Device kirim UID ke API → 
Sistem validasi UID → Cek jadwal → 
Create Attendance (status: hadir) → Notifikasi real-time
```

### 2. Permohonan Izin/Sakit
```
Siswa submit form izin/sakit (+ foto) → LeaveRequest (status: pending_teacher) →
Guru approve → status: pending_admin →
Tata Usaha approve → status: approved + create Attendance (status: izin/sakit)
```

### 3. Login Multi-Role
```
Admin/TU: email + password (hashed)
Siswa: email/NIS + tanggal lahir (YYYY-MM-DD)
Guru: email/NIP + tanggal lahir (YYYY-MM-DD)
```

### 4. Sinkronisasi Data Eksternal
```
Admin klik "Sync" → Kirim data ke http://localhost/absensi_api/absen.php →
API eksternal simpan data → Response success/fail
```

## Arsitektur Aplikasi

### Controllers
- **DashboardController**: Statistik & overview
- **AbsensiController**: CRUD absensi + sinkronisasi eksternal
- **LeaveRequestController**: Workflow izin/sakit
- **MonitoringController**: Real-time NFC monitoring
- **StudentController**: CRUD data siswa
- **TeacherController**: CRUD data guru
- **ScheduleController**: CRUD jadwal + presence checking
- **ReportController**: Laporan absensi (CSV/PDF)
- **SettingsController**: Pengaturan sistem
- **PortalController**: Portal siswa & guru
- **NotificationController**: Approval workflow

### API Controllers (Mobile App)
- **MobileAuthController**: Login student/teacher, register device
- **MobileAttendanceController**: NFC tap endpoint
- **MobileStudentController**: Profile, summary, absensi, leave requests
- **MobileTeacherController**: View absences, update attendance

### Middleware
- **Authenticate**: Standard Laravel auth
- **EnsureUserRole**: Role-based access control
- **EnsureNfcApiKey**: Validasi X-NFC-API-KEY untuk API NFC
- **VerifyCsrfToken**: CSRF protection (bypass untuk API)
- **TrimStrings**: Auto trim input
- **EncryptCookies**: Cookie encryption

### Models
- **User**: Admin/TU authentication
- **Student**: Data siswa + relasi
- **Teacher**: Data guru + relasi
- **Attendance**: Kehadiran + relasi
- **LeaveRequest**: Izin/sakit + workflow
- **Schedule**: Jadwal kelas
- **NfcDevice**: Perangkat NFC
- **Homeroom**: Wali kelas
- **StudentDevice**: Device siswa (mobile app)
- **ScanAttempt**: Log percobaan scan NFC

## Routes Penting

### Web Routes (Admin/TU)
- `/login` - Login page
- `/` - Dashboard utama
- `/monitoring/nfc` - Real-time NFC monitoring
- `/siswa` - CRUD siswa
- `/guru` - CRUD guru
- `/jadwal` - CRUD jadwal
- `/absensi` - Manajemen absensi + sync
- `/request-izin-sakit` - Approval izin/sakit
- `/laporan/absensi` - Laporan + export
- `/settings` - Pengaturan sistem
- `/profile` - Profile admin

### Portal Routes (Siswa/Guru)
- `/app/siswa/dashboard` - Dashboard siswa
- `/app/siswa/jadwal` - Jadwal siswa
- `/app/siswa/riwayat` - Riwayat absensi
- `/app/siswa/izin-sakit` - Form izin/sakit
- `/app/siswa/profil` - Profile siswa
- `/app/guru/absensi` - Input kehadiran siswa
- `/app/guru/profil` - Profile guru

### API Routes (Mobile)
- `POST /api/mobile/login/student` - Login siswa
- `POST /api/mobile/login/teacher` - Login guru
- `POST /api/mobile/attendance` - NFC tap (requires X-NFC-API-KEY)
- `GET /api/mobile/student/profile` - Profile siswa
- `GET /api/mobile/student/summary` - Ringkasan absensi
- `GET /api/mobile/student/absensi` - List absensi
- `GET /api/mobile/student/leave-requests` - List izin/sakit
- `POST /api/mobile/student/leave-requests` - Submit izin/sakit
- `GET /api/mobile/teacher/absences` - Ketidakhadiran siswa
- `POST /api/mobile/teacher/attendance` - Update kehadiran

## Fitur Khusus

### 1. Normalisasi Kelas
- Semua kelas dinormalisasi ke format: `X TEI`, `XI TEI`, `XII TEI`
- Command: `BackfillScheduleAttendance` untuk sync schedule_id

### 2. reCAPTCHA Bypass
- Konfigurasi `RECAPTCHA_BYPASS_LOCAL=true` untuk development
- Auto-detect localhost/127.0.0.1 untuk bypass

### 3. Session Handling
- Secure cookie untuk production (HTTPS)
- Session regeneration setelah login/logout
- Cache control untuk halaman login

### 4. Storage Public Path
- Support custom path untuk cPanel/shared hosting
- Environment: `STORAGE_PUBLIC_PATH=/home/user/public_html/storage`

### 5. CORS Configuration
- Allowed origins, methods, headers untuk API
- Support untuk mobile app cross-origin requests

## Environment Variables Penting

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

# Security
NFC_API_KEY=your-secret-nfc-api-key
RECAPTCHA_SITE_KEY=your-site-key
RECAPTCHA_SECRET_KEY=your-secret-key
RECAPTCHA_BYPASS_LOCAL=true

# External API
EXTERNAL_ABSENSI_API_URL=http://localhost/absensi_api/absen.php

# CORS
CORS_ALLOWED_ORIGINS=http://localhost,http://127.0.0.1
CORS_ALLOWED_METHODS=GET,POST,PUT,PATCH,DELETE,OPTIONS
CORS_ALLOWED_HEADERS=Authorization,Content-Type,Accept,X-Requested-With,X-NFC-API-KEY

# Storage
STORAGE_PUBLIC_PATH=  # Kosongkan untuk development

# Session
SESSION_DRIVER=file
SESSION_LIFETIME=120
# SESSION_SECURE_COOKIE=true  # Uncomment untuk production HTTPS
```

## Konvensi Kode

### Naming Conventions
- **Controllers**: PascalCase dengan suffix `Controller` (e.g., `AbsensiController`)
- **Models**: PascalCase singular (e.g., `Student`, `Attendance`)
- **Routes**: kebab-case (e.g., `/request-izin-sakit`)
- **Views**: kebab-case (e.g., `izin-sakit.blade.php`)
- **Database Tables**: snake_case plural (e.g., `students`, `attendances`)
- **Variables**: camelCase (e.g., `$leaveRequest`)

### Status Values
- Attendance status: `hadir`, `izin`, `sakit`, `alpa` (bukan `alpha`)
- LeaveRequest status: `pending_teacher`, `pending_admin`, `approved`, `rejected`
- Student/Teacher status: `aktif`, `nonaktif`

### Response Format (API)
```json
{
  "success": true,
  "message": "Data berhasil disimpan",
  "data": { ... }
}
```

### Error Handling
- Validation errors: return back with `withErrors()`
- API errors: JSON response dengan status code
- Try-catch untuk external API calls
- Log exceptions dengan `report($e)`

## Migration & Seeder

### Migration Order
1. Users (base table)
2. Students, Teachers (foreign to users via email)
3. Schedules (foreign to teachers)
4. NFC Devices
5. Attendances (foreign to students, devices, schedules)
6. Leave Requests (foreign to students)
7. Settings, Homerooms, Student Devices, Scan Attempts

### Important Migrations
- `2026_05_14_100000_normalize_class_names_to_tei.php` - Normalisasi kelas
- `2026_05_16_000001_rename_students_nim_to_nis.php` - Rename NIM ke NIS
- `2026_05_18_000001_add_unique_uid_kartu_to_students_table.php` - Unique UID
- `2026_05_23_124539_add_workflow_fields_to_leave_requests_table.php` - Workflow
- `2026_05_28_000002_update_attendances_alpha_to_alpa.php` - Fix typo alpha→alpa

## Testing & Development

### Artisan Commands
```bash
# Migration
php artisan migrate
php artisan migrate:fresh --seed

# Custom Commands
php artisan backfill:schedule-attendance

# Development Server
php artisan serve

# Asset Building
npm run dev
npm run build
```

### Testing Endpoints
- NFC API: Requires `X-NFC-API-KEY` header
- Mobile API: Requires `Authorization: Bearer {token}` atau `api_token` parameter
- Web routes: Requires session authentication

## Deployment Notes

### Production Checklist
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate `APP_KEY`
- [ ] Configure database credentials
- [ ] Set `SESSION_SECURE_COOKIE=true` untuk HTTPS
- [ ] Configure `STORAGE_PUBLIC_PATH` untuk shared hosting
- [ ] Disable `RECAPTCHA_BYPASS_LOCAL`
- [ ] Set proper `CORS_ALLOWED_ORIGINS`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Run `npm run build`
- [ ] Set proper file permissions (storage, bootstrap/cache)

### Common Issues
1. **UID siswa belum diatur**: Set `uid_kartu` di tabel students
2. **Migration error**: Check migration order dan foreign key constraints
3. **Session tidak persist**: Check session driver & storage permissions
4. **CORS error**: Tambahkan origin ke `CORS_ALLOWED_ORIGINS`
5. **API key invalid**: Check `NFC_API_KEY` di .env dan request header

## Referensi File Penting

### Backend
- `app/Http/Controllers/AbsensiController.php` - Sinkronisasi eksternal
- `app/Http/Controllers/LeaveRequestController.php` - Workflow approval
- `app/Http/Controllers/Api/MobileAttendanceController.php` - NFC tap handler
- `app/Http/Middleware/EnsureNfcApiKey.php` - API authentication
- `routes/web.php` - Web routes
- `routes/api.php` - API routes

### Frontend
- `resources/views/layouts/app.blade.php` - Main layout
- `resources/views/absensi/index.blade.php` - Absensi management
- `resources/views/monitoring/nfc.blade.php` - Real-time monitoring
- `resources/views/portal/` - Portal siswa & guru

### Configuration
- `.env.example` - Environment template
- `config/cors.php` - CORS configuration
- `config/services.php` - External services (reCAPTCHA)

### Documentation
- `ABSENSI_SYNC_CHANGES.md` - Changelog sinkronisasi absensi
- `README.md` - Project readme

## Tips untuk AI Development

1. **Selalu cek role user** sebelum akses data atau operasi
2. **Gunakan transaction** untuk operasi multi-step (approval workflow)
3. **Validasi UID kartu** sebelum create attendance
4. **Handle timezone** dengan Carbon::now('Asia/Jakarta')
5. **Log external API calls** untuk debugging
6. **Gunakan eager loading** untuk relasi (with() method)
7. **Cache query berat** untuk performa
8. **Sanitize user input** untuk mencegah XSS/SQL injection
9. **Follow naming conventions** yang sudah ada
10. **Test di localhost** sebelum deploy ke production

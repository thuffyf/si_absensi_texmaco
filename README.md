# SI Absensi Texmaco

Sistem informasi absensi berbasis **NFC** untuk lingkungan sekolah/vokasi Texmaco. Terdiri dari panel web (Laravel) untuk admin dan tata usaha, serta aplikasi mobile (Flutter) untuk siswa dan guru.

## Fitur Utama

### Panel Web (Admin / Tata Usaha)

- Dashboard ringkasan kehadiran
- Manajemen data **siswa** dan **guru**
- Manajemen **jadwal kelas** (X TEI, XI TEI, XII TEI)
- **Absensi** harian — otomatis dari tap NFC, sinkronisasi eksternal, atau koreksi manual
- Alur **izin & sakit** dengan persetujuan guru lalu tata usaha
- **Monitoring NFC** real-time
- **Laporan absensi** (filter, unduh CSV & PDF)
- **Pengaturan sistem** — impor siswa, ekspor, pembersihan data
- Login dengan **Google reCAPTCHA**

### Aplikasi Mobile (Siswa & Guru)

- Login siswa (username + password) dan guru (NIP + tanggal lahir)
- **Mode NFC (HCE)** — emulasi kartu NFC menggunakan UID dari backend
- Riwayat absensi dan ringkasan kehadiran bulanan
- Pengajuan izin/sakit dari aplikasi
- Guru: daftar siswa tidak hadir dan update status absensi

### Integrasi Perangkat NFC

- Endpoint tap absensi dilindungi **API key** (`X-NFC-API-KEY`)
- UID kartu (`uid_kartu`) dikelola admin di data siswa
- Absensi terhubung ke jadwal aktif berdasarkan kelas dan hari

## Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| Backend | PHP 8.1+, Laravel 10 |
| Database | MySQL |
| Frontend web | Blade, Vite, Tailwind CSS |
| API | Laravel Sanctum |
| Laporan PDF | mPDF |
| Mobile | Flutter 3.x |
| Auth web | Session + reCAPTCHA |

## Struktur Proyek

```
si_absensi_texmaco/
├── app/                    # Model, controller, middleware
├── database/migrations/    # Skema database
├── database/seeders/       # Data contoh (development)
├── resources/views/        # Tampilan Blade
├── routes/
│   ├── web.php             # Route panel admin/TU
│   └── api.php             # Route API mobile & NFC
└── mobile/                 # Aplikasi Flutter (siswa & guru)
```

## Persyaratan

- PHP >= 8.1 dengan ekstensi: `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`
- Composer
- Node.js & npm (untuk asset frontend)
- MySQL 5.7+ / MariaDB
- Flutter SDK >= 3.11 (untuk aplikasi mobile)

## Instalasi Backend

1. **Clone repositori**

   ```bash
   git clone <url-repo> si_absensi_texmaco
   cd si_absensi_texmaco
   ```

2. **Instal dependensi PHP**

   ```bash
   composer install
   ```

3. **Konfigurasi environment**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Sesuaikan `.env`, minimal:

   ```env
   APP_NAME="SI Absensi Texmaco"
   APP_URL=http://localhost:8000

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=si_absensi_texmaco
   DB_USERNAME=root
   DB_PASSWORD=

   RECAPTCHA_SITE_KEY=your_site_key
   RECAPTCHA_SECRET_KEY=your_secret_key
   NFC_API_KEY=your_secure_nfc_api_key
   ```

4. **Migrasi database**

   ```bash
   php artisan migrate
   ```

   Untuk data contoh (opsional, development):

   ```bash
   php artisan db:seed
   ```

5. **Storage & asset**

   ```bash
   php artisan storage:link
   npm install
   npm run build
   ```

6. **Jalankan server**

   ```bash
   php artisan serve
   ```

   Panel web: `http://localhost:8000/login`

## Instalasi Aplikasi Mobile

Detail tambahan ada di [`mobile/README.md`](mobile/README.md).

1. Masuk ke folder mobile:

   ```bash
   cd mobile
   ```

2. Salin dan sesuaikan environment:

   ```bash
   cp .env.example .env
   ```

   ```env
   API_BASE_URL=http://10.0.2.2:8000/api
   API_TIMEOUT_SECONDS=10
   ```

   > Gunakan IP LAN server Laravel jika menjalankan di perangkat fisik (bukan emulator Android).

3. Instal dependensi dan jalankan:

   ```bash
   flutter pub get
   flutter run
   ```

## Peran Pengguna (Role)

| Role | Akses |
|------|--------|
| `admin` | Panel web penuh |
| `tata_usaha` | Panel web penuh |
| `guru` | Dashboard guru, persetujuan izin/sakit, monitoring (via web/mobile) |
| `siswa` | Dashboard siswa, absensi, jadwal, pengajuan izin (via web/mobile) |

Login panel web (`/login`) hanya untuk role **admin** dan **tata_usaha**.

## Alur Absensi NFC

1. Admin TU mengisi **UID kartu** (`uid_kartu`) pada data siswa di panel web.
2. Siswa login di aplikasi mobile; UID disimpan di perangkat.
3. Siswa membuka **Mode NFC** — HP mengemulasikan kartu via HCE.
4. Perangkat reader NFC mengirim tap ke API:

   ```
   POST /api/mobile/attendance
   Header: X-NFC-API-KEY: <NFC_API_KEY>
   Body: { "uid_kartu": "...", "device_id": 1 }
   ```

5. Sistem mencocokkan siswa, jadwal aktif, dan menyimpan status absensi (`hadir`, `izin`, `sakit`, `alpha`, `late`).

## Alur Izin & Sakit

1. Siswa mengajukan izin/sakit (web atau mobile).
2. Status awal: `pending_teacher` → guru menyetujui/menolak.
3. Jika disetujui guru: `pending_admin` → tata usaha menyetujui/menolak.
4. Status akhir: `approved` atau `rejected` — data absensi diperbarui sesuai keputusan.

## API Mobile (Ringkasan)

| Method | Endpoint | Keterangan |
|--------|----------|------------|
| POST | `/api/mobile/login/student` | Login siswa |
| POST | `/api/mobile/login/teacher` | Login guru |
| POST | `/api/mobile/register` | Registrasi perangkat |
| POST | `/api/mobile/attendance` | Tap absensi NFC (butuh API key) |
| GET | `/api/mobile/student/profile` | Profil siswa |
| GET | `/api/mobile/student/summary` | Ringkasan kehadiran |
| GET | `/api/mobile/student/absensi` | Riwayat absensi |
| GET/POST | `/api/mobile/student/leave-requests` | Daftar / buat izin |
| GET | `/api/mobile/teacher/absences` | Siswa tidak hadir |
| POST | `/api/mobile/teacher/attendance` | Update absensi (guru) |
| GET | `/api/monitoring/nfc-data` | Data monitoring NFC (butuh API key) |

## Perintah Berguna

```bash
# Development asset (hot reload)
npm run dev

# Backfill schedule_id pada data absensi
php artisan attendance:backfill-schedule

# Format kode (jika memakai Pint)
./vendor/bin/pint
```

## Keamanan

- Jangan commit file `.env` — gunakan `.env.example` sebagai template.
- Set `NFC_API_KEY` yang kuat dan rahasiakan di perangkat reader NFC.
- Daftarkan kunci [Google reCAPTCHA](https://www.google.com/recaptcha/admin) untuk halaman login web.
- Pastikan `APP_DEBUG=false` di lingkungan produksi.

## Lisensi

Proyek ini menggunakan [Laravel](https://laravel.com), yang dirilis di bawah [MIT License](https://opensource.org/licenses/MIT).

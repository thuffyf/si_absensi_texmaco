# SI Absensi Texmaco

Sistem informasi absensi berbasis **NFC** untuk lingkungan sekolah/vokasi Texmaco. Terdiri dari panel web (Laravel) untuk admin dan tata usaha, serta portal siswa/guru yang masuk melalui pintu login yang sama.

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

### Portal Siswa & Guru

- Login siswa dan guru melalui halaman **/login** menggunakan **email + tanggal lahir**
- Riwayat absensi dan ringkasan kehadiran bulanan
- Pengajuan izin/sakit langsung dari browser handphone
- Guru: daftar siswa hadir, tidak hadir, belum absen, dan update status absensi

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
| Portal siswa/guru | Blade, Vite, Tailwind CSS |
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
│   └── api.php             # Route API NFC
└── resources/views/portal/ # Portal handphone siswa & guru
```

## Persyaratan

- PHP >= 8.1 dengan ekstensi: `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`
- Composer
- Node.js & npm (untuk asset frontend)
- MySQL 5.7+ / MariaDB
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

   Login semua role: `http://localhost:8000/login`

## Peran Pengguna (Role)

| Role | Akses |
|------|--------|
| `admin` | Panel web penuh |
| `tata_usaha` | Panel web penuh |
| `guru` | Monitoring absensi siswa via portal handphone |
| `siswa` | Dashboard, riwayat, izin/sakit, dan profil via portal handphone |

Login panel web (`/login`) hanya untuk role **admin** dan **tata_usaha**.

## Alur Absensi NFC

1. Admin TU mengisi **UID kartu** (`uid_kartu`) pada data siswa di panel web.
2. Siswa login di portal handphone untuk melihat UID kartu dan status absensi.
3. Perangkat reader NFC mengirim tap ke API:

   ```
   POST /api/mobile/attendance
   Header: X-NFC-API-KEY: <NFC_API_KEY>
   Body: { "uid_kartu": "...", "device_id": 1 }
   ```

4. Sistem mencocokkan siswa, jadwal aktif, dan menyimpan status absensi (`hadir`, `izin`, `sakit`, `alpha`, `late`).

## Alur Izin & Sakit

1. Siswa mengajukan izin/sakit melalui portal handphone.
2. Status awal pengajuan dari portal siswa: `pending_admin`.
3. Tata usaha meninjau lalu menyetujui atau menolak pengajuan.
4. Status akhir: `approved` atau `rejected` — data absensi diperbarui sesuai keputusan.

## Endpoint NFC (Ringkasan)

| Method | Endpoint | Keterangan |
|--------|----------|------------|
| POST | `/api/mobile/attendance` | Tap absensi NFC (butuh API key) |
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

# Ringkasan Perubahan Sinkronisasi Absensi

## Tujuan
Mengubah sistem dari "Izin & Sakit" menjadi "Absensi" yang terhubung ke API eksternal di `http://localhost/absensi_api/absen.php` untuk sinkronisasi data yang sempurna.

## Perubahan yang Dilakukan

### 1. Backend (Laravel)

#### Routes (`routes/web.php`)
- **Sebelumnya**: `/request-izin-sakit` dengan `LeaveRequestController`
- **Sekarang**: `/absensi` dengan `AbsensiController`
- Menambahkan route untuk sinkronisasi: `/absensi/sync`
- Mengimpor `AbsensiController`

#### Controller Baru (`app/Http/Controllers/AbsensiController.php`)
- Membuat controller baru untuk menangani absensi
- Terhubung ke API eksternal: `http://localhost/absensi_api/absen.php`
- Fitur:
  - `index()`: Menampilkan data absensi
  - `store()`: Menyimpan data absensi dan sinkronisasi ke API eksternal
  - `syncFromExternal()`: Mengambil data dari API eksternal
  - `syncToExternalApi()`: Mengirim data ke API eksternal (private)

#### API Routes (`routes/api.php`)
- Menambahkan endpoint baru: `/api/mobile/student/absensi`
- Endpoint ini digunakan oleh mobile app untuk mengambil data absensi

#### Mobile API Controller (`app/Http/Controllers/Api/MobileStudentController.php`)
- Menambahkan method `absensi()` untuk mengembalikan data absensi siswa
- Format response: tanggal, waktu, status, keterangan

### 2. Frontend (Blade Views)

#### Navigation (`resources/views/layouts/app.blade.php`)
- Mengubah menu "Izin & Sakit" menjadi "Absensi"
- Mengubah route dari `requests.izin-sakit` menjadi `absensi.index`
- Mengubah icon dari "IS" menjadi "AB"
- Mengubah aria-label notification dari "Persetujuan izin dan alpha" menjadi "Persetujuan absensi"

#### View Baru (`resources/views/absensi/index.blade.php`)
- Membuat view baru untuk manajemen absensi
- Fitur:
  - Form tambah data absensi baru
  - Tabel riwayat absensi
  - Tombol sinkronisasi dari API eksternal
  - Status badges: Hadir (hijau), Izin (kuning), Sakit (biru), Alpha (merah)

#### Settings (`resources/views/settings/index.blade.php`)
- Mengubah "Notifikasi Izin/Sakit Pending" menjadi "Notifikasi Absensi Pending"
- Mengubah deskripsi menjadi "Alert untuk data absensi yang menunggu persetujuan"

#### NFC Monitoring (`resources/views/monitoring/nfc.blade.php`)
- Mengubah label status "Izin" menjadi "Absensi"
- Mengubah label status "Sakit" menjadi "Absensi"
- Mengubah "Izin Terverifikasi" menjadi "Absensi Terverifikasi"
- Mengubah "Sakit Terverifikasi" menjadi "Absensi Terverifikasi"

### 3. Mobile App (Flutter)

#### Home Screen (`mobile/lib/screens/home_screen.dart`)
- Menambahkan import `package:http/http.dart` untuk HTTP requests
- Menambahkan state `_absensiRecords` untuk menyimpan data absensi
- Menambahkan method `_loadAbsensi()` untuk mengambil data absensi
- Menambahkan method `_syncAbsensi()` untuk sinkronisasi ke API eksternal
- Menambahkan card "Sinkronisasi Absensi" dengan tombol sinkronisasi
- Memanggil `_loadAbsensi()` di `initState()` dan `onRefresh`

#### API Client (`mobile/lib/services/api_client.dart`)
- Menambahkan method `fetchStudentAbsensi()` untuk mengambil data absensi
- Mendukung parameter `from` dan `until` untuk filter tanggal
- Endpoint: `/api/mobile/student/absensi`

#### Dependencies (`mobile/pubspec.yaml`)
- Package `http: ^1.2.2` sudah tersedia (tidak perlu ditambahkan)

## Konsistensi UI

### Pengaturan (Settings)
✅ Notifikasi diubah dari "Izin/Sakit" menjadi "Absensi"

### Laporan (Reports)
✅ Kolom Izin dan Sakit tetap ada (ini adalah status dalam absensi)

### Monitoring NFC
✅ Label status diubah menjadi "Absensi" untuk konsistensi

### Profile
✅ Tidak ada perubahan yang diperlukan (profile tidak terkait dengan absensi)

## Integrasi API Eksternal

### Endpoint API Eksternal
- URL: `http://localhost/absensi_api/absen.php`
- Actions:
  - `create`: Membuat data absensi baru
  - `get_all`: Mengambil semua data absensi
  - `sync`: Sinkronisasi data

### Data yang Dikirim ke API Eksternal
```php
[
    'nis' => $student->nis,
    'nama' => $student->name,
    'kelas' => $student->class_name,
    'jurusan' => $student->major,
    'status' => $data['status'], // hadir, izin, sakit, alpha
    'tanggal' => $data['attendance_date'],
    'waktu' => $data['attendance_time'],
    'keterangan' => $data['note'],
]
```

## Catatan Penting

1. **Migration Error**: Ada migration yang gagal karena column `nama_siswa` tidak ditemukan. Ini adalah issue pre-existing yang perlu diperbaiki secara terpisah.

2. **LeaveRequestController**: Controller lama masih ada dan dapat digunakan jika diperlukan untuk fungsi lain.

3. **View Lama**: `resources/views/requests/izin-sakit.blade.php` masih ada sebagai backup.

4. **Status Types**: Sistem masih mendukung 4 status: hadir, izin, sakit, alpha. Perubahan hanya pada terminology dan integrasi API.

## Langkah Selanjutnya

1. Pastikan API eksternal di `http://localhost/absensi_api/absen.php` sudah berjalan
2. Test endpoint sinkronisasi
3. Test mobile app untuk memastikan sinkronisasi berfungsi
4. Perbaiki migration yang gagal jika diperlukan
5. Hapus view lama jika sudah tidak diperlukan

## Ringkasan

Semua bagian sistem telah disinkronisasi:
- ✅ Backend routes dan controller
- ✅ Frontend views dan navigation
- ✅ Mobile app screens dan API client
- ✅ Konsistensi UI di Settings, Reports, NFC Monitoring, dan Profile
- ✅ Integrasi dengan API eksternal untuk sinkronisasi data

Sistem sekarang menggunakan terminology "Absensi" secara konsisten dan terhubung ke API eksternal untuk sinkronisasi data yang sempurna.

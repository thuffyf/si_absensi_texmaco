# Perbaikan: Jadwal Tidak Muncul di Portal Siswa

## Masalah
Jadwal yang sudah dibuat tidak muncul di portal siswa karena **nama kelas tidak cocok** antara data siswa dan data jadwal.

**Contoh:**
- Di tabel **siswa**: `class_name` = "X"
- Di tabel **jadwal**: `class_name` = "X TEI"
- Sistem mencari dengan **exact match**, jadi tidak ketemu

## Solusi yang Diterapkan

### 1. **Query Fleksibel di Portal Siswa** ✅
Sistem sekarang otomatis mencoba mencocokkan nama kelas dengan 2 cara:
- Exact match (sesuai data asli)
- Normalized match (dinormalisasi ke format standar: X TEI, XI TEI, XII TEI)

**File yang diubah:**
- `app/Http/Controllers/PortalController.php`
  - Method `studentDashboard()` - untuk jadwal hari ini di dashboard
  - Method `studentSchedule()` - untuk halaman jadwal lengkap
  - Ditambahkan method `normalizeClassName()` - untuk normalisasi nama kelas

### 2. **Tool Normalisasi Nama Kelas** ✅
Ditambahkan fitur untuk admin/TU agar bisa menormalisasi semua nama kelas secara batch.

**Cara menggunakan:**
1. Login sebagai Admin atau Tata Usaha
2. Buka menu **Pengaturan Sistem**
3. Scroll ke bagian **Database Management**
4. Klik tombol **🔄 Normalisasi Nama Kelas**
5. Konfirmasi
6. Sistem akan mengubah semua nama kelas menjadi format standar:
   - Semua kelas yang diawali "X" → "X TEI"
   - Semua kelas yang diawali "XI" → "XI TEI"
   - Semua kelas yang diawali "XII" → "XII TEI"

**File yang diubah:**
- `app/Http/Controllers/SettingsController.php` - method `normalizeClassNames()`
- `routes/web.php` - route `settings.normalize-classes`
- `resources/views/settings/index.blade.php` - tombol UI

## Cara Menggunakan

### Opsi 1: Jalankan Tool Normalisasi (Recommended)
1. Buka Pengaturan → Database Management
2. Klik **Normalisasi Nama Kelas**
3. Sistem akan otomatis menyamakan semua nama kelas
4. Jadwal akan langsung muncul di portal siswa

### Opsi 2: Edit Manual
1. Buka menu **Data Siswa**
2. Edit siswa yang nama kelasnya tidak standar
3. Ubah dari "X" menjadi "X TEI"
4. Simpan

ATAU

1. Buka menu **Jadwal Kelas**
2. Edit jadwal yang nama kelasnya tidak standar
3. Ubah dari "X TEI" menjadi "X" (sesuaikan dengan data siswa)
4. Simpan

## Format Nama Kelas yang Didukung

Sistem akan mengenali berbagai variasi nama kelas dan mencocokkannya dengan standar:

| Input di Database | Dinormalisasi Menjadi | Keterangan |
|-------------------|----------------------|------------|
| X                 | X TEI                | ✅ Cocok   |
| X-TEI             | X TEI                | ✅ Cocok   |
| X IPA             | X TEI                | ✅ Cocok   |
| X TEI             | X TEI                | ✅ Cocok   |
| XI                | XI TEI               | ✅ Cocok   |
| XI-TEI            | XI TEI               | ✅ Cocok   |
| XI IPS            | XI TEI               | ✅ Cocok   |
| XII               | XII TEI              | ✅ Cocok   |
| XII-TEI           | XII TEI              | ✅ Cocok   |

## Testing

Untuk memastikan perbaikan berhasil:

1. **Cek data siswa:**
   ```
   SELECT DISTINCT class_name FROM students ORDER BY class_name;
   ```

2. **Cek data jadwal:**
   ```
   SELECT DISTINCT class_name FROM schedules ORDER BY class_name;
   ```

3. **Login sebagai siswa dan cek:**
   - Dashboard → bagian "Jadwal Hari Ini"
   - Menu Jadwal → lihat jadwal lengkap per hari

## Catatan Penting

- ✅ Sistem sekarang **backward compatible** - akan tetap cocok meskipun nama kelas belum dinormalisasi
- ✅ Tool normalisasi **aman dijalankan berulang kali** - hanya mengubah yang belum standar
- ✅ Tidak mempengaruhi data absensi atau data lain
- ⚠️ Pastikan backup database sebelum menjalankan normalisasi di production

## Rollback

Jika terjadi masalah, migration sudah ada di:
```
database/migrations/2026_05_14_100000_normalize_class_names_to_tei.php
```

Rollback tidak disediakan karena data asli (IPA/IPS/dll) tidak tersimpan.

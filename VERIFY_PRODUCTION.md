# Checklist Verifikasi Production

## 1. Update Production Files

```bash
cd /home/sitexamy/public_html
bash production_update.sh
```

atau manual:

```bash
cd /home/sitexamy/public_html
git pull origin main
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:cache
```

## 2. Verifikasi Perubahan File

### Cek commit terakhir:
```bash
git log --oneline -1
```

Seharusnya: **51c5303 benerin total scan dan blur**

### Cek file DashboardController:
```bash
grep -n "validAttendances" app/Http/Controllers/DashboardController.php
```

Seharusnya muncul baris yang mengandung `$validAttendances`

### Cek file MonitoringController:
```bash
grep -n "totalAttendanceToday" app/Http/Controllers/MonitoringController.php
```

Seharusnya muncul baris yang mengandung `$totalAttendanceToday`

### Cek modal students:
```bash
grep -n "z-\[100\]" resources/views/students/index.blade.php
```

Seharusnya muncul 2 baris (modal tambah dan edit)

### Cek modal teachers:
```bash
grep -n "z-\[100\]" resources/views/teachers/index.blade.php
```

Seharusnya muncul 2 baris (modal tambah dan edit)

## 3. Test di Browser

### A. Test Dashboard Sinkronisasi
1. Buka: https://sitexa.my.id/
2. Cek angka "Hadir Hari Ini" 
3. Buka: https://sitexa.my.id/monitoring/nfc
4. Cek angka "Total Scans" dan "Success"
5. **Kedua halaman harus menunjukkan angka yang sama**

### B. Test Modal Blur Full Page
1. Buka: https://sitexa.my.id/students
2. Klik tombol "Tambah Siswa"
3. **Cek apakah:**
   - ✅ Sidebar navigation menu ikut ter-blur
   - ✅ Seluruh halaman di belakang modal ter-blur
   - ✅ Hanya modal form yang jelas/tidak blur
4. Tutup modal (klik X atau klik di luar modal)
5. Klik tombol "Edit" di salah satu siswa
6. **Cek apakah blur juga berfungsi**

### C. Test Remember Me
1. Buka: https://sitexa.my.id/login
2. Centang "Ingat saya"
3. Login dengan username dan password
4. Tutup browser
5. Buka browser lagi dan akses https://sitexa.my.id
6. **Seharusnya langsung masuk tanpa login lagi**
7. Logout
8. Buka login lagi
9. **Username seharusnya sudah terisi otomatis**

## 4. Clear Browser Cache

Jika perubahan belum terlihat di browser:

### Chrome/Edge:
- Tekan `Ctrl + Shift + Delete`
- Pilih "Cached images and files"
- Klik "Clear data"

### Hard Refresh:
- Windows: `Ctrl + F5` atau `Ctrl + Shift + R`
- Mac: `Cmd + Shift + R`

### Private/Incognito Window:
- Buka private window: `Ctrl + Shift + N`
- Test di sana untuk memastikan bukan masalah cache

## 5. Troubleshooting

### Jika masih tidak sinkron:

```bash
# Cek laravel log untuk error
tail -50 /home/sitexamy/public_html/storage/logs/laravel.log

# Cek permission
ls -la storage/
ls -la bootstrap/cache/

# Fix permission jika perlu
chmod -R 755 storage bootstrap/cache
```

### Jika modal blur tidak bekerja:

```bash
# Pastikan file view sudah ter-update
cat resources/views/students/index.blade.php | grep -A 3 "add-student-modal"

# Seharusnya muncul:
# <div id="add-student-modal" class="hidden fixed inset-0 z-[100] ...">
#     <!-- Backdrop -->
#     <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" ...>
```

### Jika git pull error:

```bash
# Cek status git
git status

# Jika ada conflict atau perubahan local
git stash
git pull origin main
git stash pop
```

## 6. Logs Penting

### Laravel Log:
```bash
tail -100 storage/logs/laravel.log
```

### PHP Error Log:
```bash
tail -50 /home/sitexamy/logs/error_log
```

### Apache Error Log (jika ada akses):
```bash
tail -50 /var/log/apache2/error.log
```

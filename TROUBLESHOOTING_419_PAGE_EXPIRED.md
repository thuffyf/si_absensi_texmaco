# Troubleshooting: Error 419 - Page Expired

## Masalah
Saat login sebagai guru atau user lain, muncul error **419 Page Expired**.

## Penyebab Umum

### 1. **Session Expired (Paling Sering)**
- User terlalu lama di halaman login sebelum menekan tombol masuk
- Session lifetime (default 120 menit) sudah habis

### 2. **Permission Folder Session**
- Folder `storage/framework/sessions` tidak bisa ditulis
- Ownership atau permission salah

### 3. **HTTPS/Cookie Issue**
- Site menggunakan HTTPS tapi `SESSION_SECURE_COOKIE` tidak diset
- Cookie tidak tersimpan karena masalah domain

### 4. **Cache Config**
- Konfigurasi ter-cache dengan setting lama

## Solusi yang Sudah Diterapkan

### ✅ **1. Auto-Refresh CSRF Token**
Form login sekarang otomatis me-refresh CSRF token setiap 30 menit untuk mencegah expired.

**File:** `resources/views/auth/login.blade.php`

### ✅ **2. Custom Exception Handler**
Error 419 sekarang ditangkap dan redirect ke login dengan pesan yang jelas.

**File:** `app/Exceptions/Handler.php`

### ✅ **3. Loading State pada Button**
Button "MASUK" menampilkan loading spinner saat submit untuk feedback ke user.

## Cara Troubleshoot di Server Hosting

### **Step 1: Cek Permission Folder**
```bash
# Pastikan folder session bisa ditulis
chmod -R 775 storage/framework/sessions
chown -R www-data:www-data storage/framework/sessions

# Atau di cPanel, set permission folder ke 755
```

### **Step 2: Cek File .env**
```env
# Untuk HTTPS (production)
SESSION_SECURE_COOKIE=true
SESSION_DOMAIN=.yourdomain.com

# Untuk HTTP (development)
# SESSION_SECURE_COOKIE tidak perlu diset (default false)
```

### **Step 3: Clear Cache**
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### **Step 4: Cek Session Driver**
```env
# Pastikan menggunakan file driver (default)
SESSION_DRIVER=file

# Atau gunakan database jika file bermasalah:
# SESSION_DRIVER=database
# Lalu jalankan: php artisan session:table
# Dan: php artisan migrate
```

### **Step 5: Test Session**
Buat file test di root: `test_session.php`
```php
<?php
session_start();
$_SESSION['test'] = 'working';
echo 'Session test: ' . $_SESSION['test'];
echo '<br>Session ID: ' . session_id();
echo '<br>Session save path: ' . session_save_path();
```

Akses via browser, jika error → masalah di PHP session, bukan Laravel.

### **Step 6: Increase Session Lifetime**
```env
# Naikkan jadi 12 jam jika user sering idle di halaman login
SESSION_LIFETIME=720
```

## Quick Fix untuk User

### **Opsi 1: Reload Halaman Login**
1. Jika muncul error 419, **jangan klik back button**
2. Klik logo atau reload halaman (Ctrl+R / Cmd+R)
3. Login kembali dengan fresh token

### **Opsi 2: Clear Browser Cache**
1. Tekan Ctrl+Shift+Delete (Windows) atau Cmd+Shift+Delete (Mac)
2. Pilih "Cookies and Site Data"
3. Clear
4. Buka halaman login lagi

### **Opsi 3: Private/Incognito Mode**
1. Buka browser dalam mode private/incognito
2. Coba login dari sana
3. Jika berhasil → masalah di browser cache/cookies

## Testing Checklist

- [ ] Login sebagai Admin/TU → berhasil
- [ ] Login sebagai Siswa → berhasil
- [ ] Login sebagai Guru → berhasil
- [ ] Tunggu 30 menit di halaman login, lalu login → token auto-refresh
- [ ] Tunggu 2 jam+ di halaman login, lalu login → redirect dengan pesan error yang jelas
- [ ] Clear browser cookies, lalu login → berhasil
- [ ] Test di browser berbeda (Chrome, Firefox, Safari) → semua berhasil

## Monitoring

### Log Session Errors
```bash
# Check Laravel log
tail -f storage/logs/laravel.log | grep -i "session\|419\|csrf"

# Check PHP error log
tail -f /var/log/php-fpm/error.log
```

### Check Session Files
```bash
# Lihat session files yang aktif
ls -lah storage/framework/sessions/

# Hitung jumlah session
ls storage/framework/sessions/ | wc -l

# Lihat isi session terakhir (untuk debug)
ls -t storage/framework/sessions/ | head -1 | xargs -I {} cat storage/framework/sessions/{}
```

## Prevention

### For Production:
1. **Enable HTTPS** dan set `SESSION_SECURE_COOKIE=true`
2. **Set proper SESSION_DOMAIN** jika multi-subdomain
3. **Monitor session folder size** - cleanup jika terlalu banyak file lama
4. **Backup session config** sebelum deploy

### For Development:
1. **Jangan cache config** di development
2. **Use .env file** bukan hard-code
3. **Test dengan berbagai role** (admin, guru, siswa)

## Common Mistakes

❌ **SALAH:**
```env
# Di .env production pakai HTTPS tapi tidak set secure cookie
APP_URL=https://example.com
# SESSION_SECURE_COOKIE tidak diset → cookies tidak dikirim
```

✅ **BENAR:**
```env
APP_URL=https://example.com
SESSION_SECURE_COOKIE=true
SESSION_DOMAIN=.example.com
```

---

❌ **SALAH:**
```bash
# Cache config dengan .env lama
php artisan config:cache
# Edit .env baru
# Config masih pakai yang lama!
```

✅ **BENAR:**
```bash
# Edit .env dulu
php artisan config:clear
php artisan config:cache
```

## Kontak Support

Jika masalah masih berlanjut setelah mengikuti semua langkah:
1. Cek Laravel log: `storage/logs/laravel.log`
2. Cek PHP error log
3. Cek web server error log (nginx/apache)
4. Screenshot error message
5. Catat langkah yang sudah dicoba

# 🚀 Deployment Guide - Fitur Forgot Password (Production Ready)

## 📋 Overview

Panduan deployment lengkap untuk implementasi fitur **Forgot Password** pada Sistem Absensi NFC Texmaco yang sudah dihosting. Fitur ini menggunakan SMTP hosting `mail.sitexa.my.id` dan siap untuk production.

---

## ✅ Pre-Deployment Checklist

Sebelum deploy, pastikan semua file berikut sudah ada:

### Backend Files
- [x] `app/Http/Controllers/Auth/PasswordResetController.php`
- [x] `app/Notifications/ResetPasswordNotification.php`
- [x] `app/Models/User.php` (modified)
- [x] `routes/web.php` (modified)

### Frontend Files
- [x] `resources/views/auth/passwords/email.blade.php`
- [x] `resources/views/auth/passwords/reset.blade.php`
- [x] `resources/views/auth/passwords/contact-admin.blade.php`
- [x] `resources/views/auth/login.blade.php` (modified)

### Database
- [x] Migration `create_password_reset_tokens_table.php` (sudah ada)

### Configuration
- [x] `.env` (updated dengan SMTP settings)
- [x] `.env.example` (updated)

---

## 🔧 Step 1: Update Konfigurasi Email

### File: `.env`

Update konfigurasi email di file `.env` dengan settings production:

```env
# Mail Configuration (Production SMTP)
MAIL_MAILER=smtp
MAIL_HOST=mail.sitexa.my.id
MAIL_PORT=465
MAIL_USERNAME=absensi@sitexa.my.id
MAIL_PASSWORD=sitexadmintu123
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=absensi@sitexa.my.id
MAIL_FROM_NAME="Sistem Absensi Sitexa"
```

**⚠️ PENTING**: 
- Port `465` menggunakan encryption `ssl`
- Port `587` menggunakan encryption `tls`
- Gunakan sesuai konfigurasi mail server

---

## 🧪 Step 2: Testing di Local

### 2.1 Test Koneksi SMTP

Jalankan command berikut untuk test koneksi:

```bash
php artisan tinker
```

Kemudian di Tinker console:

```php
use Illuminate\Support\Facades\Mail;

Mail::raw('Test koneksi SMTP dari local', function ($message) {
    $message->to('your-email@domain.com')
            ->subject('Test Email - Sistem Absensi');
});

echo "Email sent! Check your inbox.";
exit;
```

**Expected Result**: Email diterima di inbox `your-email@domain.com`

### 2.2 Test Forgot Password Flow

#### Test 1: Request Reset Password (Guru/Siswa)
```bash
# 1. Buka browser
http://localhost/login

# 2. Klik "Lupa password?"
# 3. Masukkan email: guru@test.com (atau email guru/siswa yang ada)
# 4. Klik "Kirim Link Reset"
# 5. Cek inbox email guru@test.com
# 6. Klik link di email
# 7. Masukkan password baru
# 8. Submit → redirect ke login
# 9. Login dengan password baru
```

**Expected Results**:
- ✅ Email diterima dalam 1-2 menit
- ✅ Link format: `http://localhost/password/reset/{token}?email=guru@test.com`
- ✅ Halaman reset tampil dengan email pre-filled
- ✅ Password strength indicator berfungsi
- ✅ Setelah submit → redirect ke login dengan pesan sukses
- ✅ Login dengan password baru berhasil

#### Test 2: Admin/TU Restriction
```bash
# 1. Buka http://localhost/password/reset
# 2. Masukkan email: admin@texmaco.local
# 3. Klik "Kirim Link Reset"
# 4. Redirect ke /password/contact-admin
```

**Expected Result**: 
- ✅ Tidak ada email terkirim
- ✅ Redirect ke halaman contact administrator

#### Test 3: Rate Limiting
```bash
# 1. Request reset 3x dengan email yang sama
# 2. Request ke-4 harus error
```

**Expected Result**: 
- ✅ Error: "Terlalu banyak percobaan. Silakan coba lagi dalam X menit."

#### Test 4: Token Expiration
```bash
# 1. Request reset password
# 2. Tunggu 61 menit (atau ubah config expire ke 1 untuk testing)
# 3. Coba gunakan link
```

**Expected Result**: 
- ✅ Error: "This password reset token is invalid."

#### Test 5: Token Single Use
```bash
# 1. Request reset
# 2. Gunakan link untuk reset password
# 3. Coba gunakan link yang sama lagi
```

**Expected Result**: 
- ✅ Error: "This password reset token is invalid."

---

## 📦 Step 3: Prepare for Production Deploy

### 3.1 Clear All Caches

```bash
# Clear application cache
php artisan cache:clear

# Clear configuration cache
php artisan config:clear

# Clear route cache
php artisan route:clear

# Clear view cache
php artisan view:clear

# Clear compiled classes
php artisan clear-compiled
```

### 3.2 Optimize untuk Production

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

### 3.3 Build Assets

```bash
# Install dependencies (jika belum)
npm install

# Build untuk production
npm run build
```

---

## 🌐 Step 4: Deploy ke Production Server

### 4.1 Upload Files via FTP/SSH

Upload semua file berikut ke server production:

#### Backend
```
app/Http/Controllers/Auth/PasswordResetController.php
app/Notifications/ResetPasswordNotification.php
app/Models/User.php
```

#### Frontend
```
resources/views/auth/passwords/email.blade.php
resources/views/auth/passwords/reset.blade.php
resources/views/auth/passwords/contact-admin.blade.php
resources/views/auth/login.blade.php
```

#### Routes
```
routes/web.php
```

#### Assets (jika perlu)
```
public/build/ (hasil npm run build)
```

### 4.2 Update .env di Production

**⚠️ JANGAN upload file .env dari local!**

Edit `.env` di server production manual:

```bash
# Via SSH
nano /path/to/project/.env

# Atau via cPanel File Manager
```

Tambahkan/update konfigurasi email:

```env
MAIL_MAILER=smtp
MAIL_HOST=mail.sitexa.my.id
MAIL_PORT=465
MAIL_USERNAME=absensi@sitexa.my.id
MAIL_PASSWORD=sitexadmintu123
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=absensi@sitexa.my.id
MAIL_FROM_NAME="Sistem Absensi Sitexa"
```

### 4.3 Set Permissions (jika perlu)

```bash
# Jika menggunakan SSH
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 4.4 Clear & Cache di Production

```bash
# Via SSH
cd /path/to/project

php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🧪 Step 5: Testing di Production

### 5.1 Test Koneksi SMTP Production

```bash
# Via SSH
php artisan tinker
```

```php
Mail::raw('Test Production SMTP', function ($message) {
    $message->to('your-email@domain.com')
            ->subject('Test Production - Sistem Absensi');
});
```

**Expected**: Email diterima dari `absensi@sitexa.my.id`

### 5.2 Test Forgot Password Flow Production

#### URL Production
Ganti `localhost` dengan domain production:
```
https://yourdomain.com/login
https://yourdomain.com/password/reset
```

#### Test Scenarios

**Scenario 1: Reset Password Guru**
```
1. Buka https://yourdomain.com/login
2. Klik "Lupa password?"
3. Input: email-guru-real@domain.com
4. Cek inbox email guru
5. Klik link (format: https://yourdomain.com/password/reset/{token})
6. Input password baru
7. Submit
8. Login dengan password baru
```

**Scenario 2: Reset Password Siswa**
```
1. Ulangi langkah di atas dengan email siswa real
```

**Scenario 3: Email Tidak Terdaftar**
```
1. Input: email-tidak-terdaftar@test.com
2. Submit
3. Tetap tampil pesan sukses (security: email enumeration prevention)
4. Tidak ada email terkirim
```

**Scenario 4: Admin/TU**
```
1. Input: email admin/tata usaha
2. Submit
3. Redirect ke /password/contact-admin
4. Tampil instruksi hubungi administrator
```

---

## 📊 Step 6: Monitoring & Verification

### 6.1 Check Email Delivery

Monitor apakah email terkirim:

```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Look for:
# - Email sent successfully
# - SMTP connection errors
# - Authentication failures
```

### 6.2 Check Database

Verifikasi token tersimpan di database:

```sql
-- Login ke database production
mysql -u username -p database_name

-- Check password reset tokens
SELECT * FROM password_reset_tokens ORDER BY created_at DESC LIMIT 5;

-- Verify token created when user requests reset
```

### 6.3 Monitor Rate Limiting

```php
// Di tinker production
use Illuminate\Support\Facades\RateLimiter;

// Check attempts for specific email
$key = 'password-reset:' . request()->ip() . ':email@example.com';
$attempts = RateLimiter::attempts($key);
echo "Attempts: $attempts\n";

// Clear rate limit (jika testing)
RateLimiter::clear($key);
```

---

## 🔍 Troubleshooting Production

### Problem 1: Email Tidak Terkirim

**Symptoms**: User tidak menerima email reset password

**Solutions**:

1. **Check SMTP Credentials**
   ```bash
   # Verify .env settings
   php artisan config:show mail
   ```

2. **Test SMTP Connection**
   ```bash
   php artisan tinker
   Mail::raw('Test', fn($m) => $m->to('test@test.com')->subject('Test'));
   ```

3. **Check Firewall**
   ```bash
   # Pastikan port 465 tidak diblock
   telnet mail.sitexa.my.id 465
   ```

4. **Check Laravel Logs**
   ```bash
   tail -100 storage/logs/laravel.log | grep -i mail
   ```

5. **Verify Email Credentials**
   - Login manual ke webmail: https://mail.sitexa.my.id
   - Username: absensi@sitexa.my.id
   - Password: sitexadmintu123
   - Jika gagal → credentials salah

### Problem 2: SSL/TLS Error

**Symptoms**: 
```
Connection could not be established with host mail.sitexa.my.id
stream_socket_enable_crypto(): SSL operation failed
```

**Solutions**:

1. **Coba Port & Encryption Lain**
   ```env
   # Option 1: SSL port 465
   MAIL_PORT=465
   MAIL_ENCRYPTION=ssl

   # Option 2: TLS port 587
   MAIL_PORT=587
   MAIL_ENCRYPTION=tls
   ```

2. **Disable SSL Verification (last resort)**
   Edit `config/mail.php`:
   ```php
   'smtp' => [
       'transport' => 'smtp',
       // ... other settings
       'stream' => [
           'ssl' => [
               'allow_self_signed' => true,
               'verify_peer' => false,
               'verify_peer_name' => false,
           ],
       ],
   ],
   ```

### Problem 3: Token Invalid

**Symptoms**: "This password reset token is invalid"

**Solutions**:

1. **Check Token Expiration**
   ```php
   // config/auth.php
   'passwords' => [
       'users' => [
           'expire' => 60, // 60 menit
       ],
   ],
   ```

2. **Clear Password Reset Tokens**
   ```sql
   DELETE FROM password_reset_tokens WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 HOUR);
   ```

3. **Request New Reset**
   User harus request reset ulang

### Problem 4: Rate Limit Terlalu Ketat

**Symptoms**: User di-block terlalu cepat

**Solutions**:

1. **Adjust Rate Limit**
   Edit `PasswordResetController.php`:
   ```php
   // Ubah dari 3 menjadi 5 request per jam
   if (RateLimiter::tooManyAttempts($key, 5)) {
   ```

2. **Clear Rate Limiter**
   ```bash
   php artisan cache:clear
   ```

### Problem 5: Email Masuk Spam

**Symptoms**: Email reset masuk ke folder spam

**Solutions**:

1. **Setup SPF Record**
   Tambah SPF record di DNS:
   ```
   TXT @ "v=spf1 include:mail.sitexa.my.id ~all"
   ```

2. **Setup DKIM**
   Konsultasi dengan hosting provider untuk setup DKIM

3. **User Instructions**
   Tambahkan di halaman request reset:
   ```
   "Tidak menerima email? Cek folder spam atau promotions"
   ```

---

## 📝 Testing Scenarios (Complete)

### Scenario Matrix

| # | Role | Email Status | Expected Result |
|---|------|-------------|-----------------|
| 1 | Guru | Terdaftar | ✅ Email terkirim, reset berhasil |
| 2 | Siswa | Terdaftar | ✅ Email terkirim, reset berhasil |
| 3 | Admin | Terdaftar | ✅ Redirect ke contact-admin |
| 4 | Tata Usaha | Terdaftar | ✅ Redirect ke contact-admin |
| 5 | Any | Tidak terdaftar | ✅ Pesan sukses (no email sent) |
| 6 | Guru | Terdaftar (3x) | ✅ Rate limit error |
| 7 | Siswa | Token expired | ❌ Invalid token error |
| 8 | Guru | Token used 2x | ❌ Invalid token error |

### Test Script

```bash
#!/bin/bash
# test-forgot-password.sh

echo "=== Testing Forgot Password Feature ==="

# Test 1: Request reset untuk guru
echo "Test 1: Request reset untuk guru"
curl -X POST https://yourdomain.com/password/email \
  -d "email=guru@test.com" \
  -d "_token=TOKEN"

# Test 2: Request reset untuk admin (should redirect)
echo "Test 2: Request reset untuk admin"
curl -X POST https://yourdomain.com/password/email \
  -d "email=admin@texmaco.local" \
  -d "_token=TOKEN"

# Add more tests...
```

---

## 🎯 Production Deployment Checklist

### Pre-Deployment
- [ ] Semua file sudah di-upload ke production
- [ ] `.env` production sudah diupdate dengan SMTP settings
- [ ] Permissions sudah di-set (755 untuk storage & bootstrap/cache)
- [ ] Cache sudah di-clear

### Testing
- [ ] Test SMTP connection via tinker
- [ ] Test forgot password flow dengan email real guru
- [ ] Test forgot password flow dengan email real siswa
- [ ] Test admin/TU redirect ke contact-admin
- [ ] Test rate limiting (3 request)
- [ ] Test email masuk inbox (bukan spam)
- [ ] Test password strength indicator
- [ ] Test responsive di mobile

### Post-Deployment
- [ ] Monitor logs selama 24 jam pertama
- [ ] Verify tidak ada error di `storage/logs/laravel.log`
- [ ] Test dari berbagai browser (Chrome, Firefox, Safari, Edge)
- [ ] Test dari berbagai device (desktop, tablet, mobile)
- [ ] Backup database sebelum & setelah deploy
- [ ] Document any issues yang ditemukan

### User Communication
- [ ] Inform users bahwa fitur forgot password sudah tersedia
- [ ] Provide instructions cara menggunakan
- [ ] Setup FAQ untuk common issues
- [ ] Provide contact support jika ada masalah

---

## 📞 Support & Maintenance

### Log Monitoring

```bash
# Monitor logs real-time
tail -f storage/logs/laravel.log | grep -i "password\|mail\|smtp"

# Check error logs
grep -i "error\|exception" storage/logs/laravel.log | tail -20

# Check email sent count (daily)
grep -c "Message-ID:" storage/logs/laravel.log
```

### Database Cleanup

Bersihkan token expired secara berkala:

```sql
-- Hapus token yang sudah lebih dari 1 hari
DELETE FROM password_reset_tokens 
WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 DAY);

-- Atau buat cron job
# /etc/cron.daily/cleanup-password-tokens
```

```php
// Command Laravel (optional)
php artisan make:command CleanupPasswordTokens

// Tambah ke Kernel.php schedule
$schedule->command('tokens:cleanup')->daily();
```

### Performance Monitoring

```php
// Monitor email queue (jika pakai queue)
php artisan queue:work --tries=3

// Monitor failed jobs
SELECT * FROM failed_jobs WHERE payload LIKE '%ResetPassword%';
```

---

## ✅ Success Criteria

Fitur dianggap berhasil di-deploy jika:

1. ✅ **Email Delivery**: Email reset terkirim dalam < 2 menit
2. ✅ **Token Validity**: Token berfungsi dalam 60 menit
3. ✅ **Security**: Rate limiting & validation berfungsi
4. ✅ **UX**: UI responsive & user-friendly
5. ✅ **No Breaking Changes**: Fitur lain tetap berfungsi normal
6. ✅ **Admin Protection**: Admin/TU tidak bisa reset via email
7. ✅ **Zero Downtime**: Deploy tanpa mengganggu user aktif

---

## 🎉 Deployment Complete!

Setelah semua checklist selesai, fitur Forgot Password siap digunakan di production! 

**Next Steps**:
1. Monitor logs selama 24-48 jam pertama
2. Gather user feedback
3. Fix any issues yang muncul
4. Document lessons learned

**Happy Deploying! 🚀**

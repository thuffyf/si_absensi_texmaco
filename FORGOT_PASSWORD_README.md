# Fitur Forgot Password - Quick Start Guide

## ✨ Fitur yang Tersedia

Sistem Absensi NFC Texmaco sekarang memiliki fitur **Forgot Password** yang lengkap dan aman untuk Guru dan Siswa.

### Untuk Guru & Siswa
✅ Reset password via email dengan link aman
✅ Link berlaku 60 menit
✅ Token sekali pakai
✅ Password strength indicator
✅ Rate limiting (3 request/jam)

### Untuk Admin & Tata Usaha
⚠️ **Tidak bisa reset via email** (email fiktif)
✅ Redirect ke halaman instruksi kontak administrator

## 🚀 Quick Setup (5 Menit)

### 1. Konfigurasi Email

Edit file `.env` dan tambahkan:

```env
# UNTUK TESTING (Mailtrap - GRATIS)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@texmaco.sch.id
MAIL_FROM_NAME="Sistem Absensi NFC Texmaco"

# UNTUK PRODUCTION (Gmail)
# MAIL_MAILER=smtp
# MAIL_HOST=smtp.gmail.com
# MAIL_PORT=587
# MAIL_USERNAME=your-email@gmail.com
# MAIL_PASSWORD=your-gmail-app-password
# MAIL_ENCRYPTION=tls
# MAIL_FROM_ADDRESS=noreply@texmaco.sch.id
# MAIL_FROM_NAME="Sistem Absensi NFC Texmaco"
```

### 2. Testing di Mailtrap (Development)

1. **Daftar gratis** di https://mailtrap.io
2. **Copy credentials** dari dashboard → Inbox → SMTP Settings
3. **Paste ke .env** (MAIL_USERNAME dan MAIL_PASSWORD)
4. **Test**:
   ```bash
   php artisan tinker
   Mail::raw('Test', fn($m) => $m->to('test@test.com')->subject('Test'));
   ```
5. **Cek inbox** di Mailtrap dashboard

### 3. Cara Menggunakan

#### Sebagai User (Guru/Siswa):
1. Buka halaman login: `http://localhost/login`
2. Klik **"Lupa password?"**
3. Masukkan email yang terdaftar
4. Klik **"Kirim Link Reset"**
5. Cek email inbox (atau Mailtrap jika development)
6. Klik link di email
7. Masukkan password baru (min 8 karakter, huruf besar+kecil+angka)
8. Login dengan password baru

#### Sebagai Admin/TU:
1. Klik "Lupa password?" → redirect ke halaman instruksi
2. Hubungi administrator sistem untuk reset manual

## 📧 Setup Gmail untuk Production

### Langkah-langkah:

1. **Enable 2-Factor Authentication**
   - Buka https://myaccount.google.com/security
   - Aktifkan "2-Step Verification"

2. **Generate App Password**
   - Buka https://myaccount.google.com/apppasswords
   - Pilih "Mail" dan "Other (Custom name)"
   - Nama: "Texmaco Absensi"
   - Copy password (format: xxxx-xxxx-xxxx-xxxx)

3. **Update .env Production**
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your-gmail@gmail.com
   MAIL_PASSWORD=xxxx-xxxx-xxxx-xxxx
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=noreply@texmaco.sch.id
   MAIL_FROM_NAME="Sistem Absensi NFC Texmaco"
   ```

4. **Test di Production**
   ```bash
   php artisan tinker
   Mail::raw('Test Production', function($m) {
       $m->to('your-real-email@domain.com')
         ->subject('Test dari Production');
   });
   ```

## 🔒 Fitur Keamanan

### Rate Limiting
- Maksimal **3 request per jam** per IP/email
- Mencegah spam dan brute force
- Error message: "Terlalu banyak percobaan. Coba lagi dalam X menit"

### Token Security
- Link berlaku **60 menit** (configurable)
- Token **sekali pakai** - invalid setelah digunakan
- Token random 60 karakter

### Email Enumeration Prevention
- Pesan sukses **sama** untuk email terdaftar atau tidak
- Tidak leak informasi user yang terdaftar
- Message: "Jika email terdaftar, link telah dikirim"

### Password Strength
- Minimal **8 karakter**
- Harus ada **huruf besar** (A-Z)
- Harus ada **huruf kecil** (a-z)
- Harus ada **angka** (0-9)
- Real-time strength indicator di UI

### Admin Protection
- Admin/TU **tidak bisa** reset via email
- Redirect ke halaman contact administrator
- Mencegah unauthorized access

## 🎯 Routes yang Tersedia

```
GET  /password/reset              → Form request reset
POST /password/email              → Kirim email reset
GET  /password/reset/{token}      → Form reset password
POST /password/reset              → Proses reset password
GET  /password/contact-admin      → Instruksi untuk admin
```

## 📂 File-file Penting

### Backend
```
app/Http/Controllers/Auth/PasswordResetController.php
app/Notifications/ResetPasswordNotification.php
app/Models/User.php
routes/web.php
```

### Views
```
resources/views/auth/passwords/email.blade.php
resources/views/auth/passwords/reset.blade.php
resources/views/auth/passwords/contact-admin.blade.php
resources/views/auth/login.blade.php
```

### Database
```
database/migrations/2014_10_12_100000_create_password_reset_tokens_table.php
```

## ❓ Troubleshooting

### Email tidak terkirim?
1. ✅ Cek `.env` - pastikan MAIL_* sudah benar
2. ✅ Cek logs: `storage/logs/laravel.log`
3. ✅ Cek spam folder
4. ✅ Test manual: `php artisan tinker` → `Mail::raw(...)`
5. ✅ Pastikan firewall tidak block port 587

### Token invalid?
1. ✅ Token berlaku 60 menit - request ulang
2. ✅ Token sekali pakai - jangan gunakan 2x
3. ✅ Clear browser cache

### Rate limit terlalu ketat?
Edit `PasswordResetController.php`:
```php
// Ubah dari 3 menjadi 5 request
if (RateLimiter::tooManyAttempts($key, 5)) {
```

### Admin masih bisa request?
Cek `PasswordResetController::sendResetLinkEmail()` - pastikan role check ada:
```php
if ($user && in_array($user->role, ['admin', 'tata_usaha'])) {
    return redirect()->route('password.contact-admin')...
}
```

## 📖 Dokumentasi Lengkap

Untuk dokumentasi lengkap, lihat file:
- `FORGOT_PASSWORD_SETUP.md` - Setup detail & configuration
- `FORGOT_PASSWORD_README.md` - Quick start (file ini)

## 🆘 Support

**Email tidak terkirim?** → Cek `storage/logs/laravel.log`

**Butuh bantuan?** → Hubungi team developer

**Production issue?** → Check email credentials di `.env` production

## ✅ Testing Checklist

Sebelum deploy ke production:

- [ ] Test email delivery (development)
- [ ] Test reset dengan email guru
- [ ] Test reset dengan email siswa
- [ ] Test admin redirect ke contact-admin
- [ ] Test rate limiting (3 request)
- [ ] Test token expiration (60 menit)
- [ ] Test password validation
- [ ] Test dengan Gmail (production)
- [ ] Verifikasi email masuk inbox (bukan spam)
- [ ] Test di browser berbeda

## 🎉 Selesai!

Fitur Forgot Password sudah siap digunakan. Happy coding! 🚀

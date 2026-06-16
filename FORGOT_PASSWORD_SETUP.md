# Panduan Setup Fitur Forgot Password

## Overview

Fitur Forgot Password memungkinkan pengguna (Guru dan Siswa) untuk mereset password mereka melalui email. Admin dan Tata Usaha **tidak bisa** menggunakan fitur ini karena email mereka bersifat fiktif/internal.

## Fitur Utama

✅ **Reset password via email** untuk Guru dan Siswa
✅ **Rate limiting** - maksimal 3 request per jam per IP/email
✅ **Token kedaluwarsa** - link reset berlaku 60 menit (default Laravel)
✅ **Token sekali pakai** - otomatis invalid setelah digunakan
✅ **Password strength indicator** - menampilkan kekuatan password real-time
✅ **Security enhancement** - tidak menampilkan info apakah email terdaftar
✅ **Redirect ke contact admin** - untuk admin/tata usaha
✅ **Custom email template** - email branded dengan logo Texmaco

## Alur Kerja

### 1. Request Reset Password
```
User klik "Lupa Password" → Input email → 
Sistem cek role (admin/TU redirect ke contact-admin) →
Kirim email dengan token → Tampilkan pesan sukses
```

### 2. Reset Password
```
User klik link di email → Validasi token → 
Input password baru + konfirmasi → Validasi strength →
Update password (Hash::make) → Redirect ke login
```

### 3. Rate Limiting
```
Key: password-reset:{IP}:{email}
Limit: 3 requests per 1 jam
Error: "Terlalu banyak percobaan. Silakan coba lagi dalam X menit."
```

## Konfigurasi Email

### 1. Environment Variables (.env)

Tambahkan konfigurasi email di file `.env`:

```env
# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@texmaco.sch.id
MAIL_FROM_NAME="Sistem Absensi NFC Texmaco"
```

### 2. Gmail Setup (Recommended)

Jika menggunakan Gmail:

1. **Enable 2-Factor Authentication** di akun Gmail Anda
2. **Generate App Password**:
   - Buka https://myaccount.google.com/apppasswords
   - Pilih "Mail" dan "Other (Custom name)"
   - Beri nama "Texmaco Absensi"
   - Copy password yang dihasilkan
3. **Update .env**:
   ```env
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your-email@gmail.com
   MAIL_PASSWORD=xxxx-xxxx-xxxx-xxxx  # App Password
   MAIL_ENCRYPTION=tls
   ```

### 3. Mailtrap (Development/Testing)

Untuk testing di development:

1. **Daftar di Mailtrap.io** (gratis)
2. **Copy credentials** dari dashboard
3. **Update .env**:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your-mailtrap-username
   MAIL_PASSWORD=your-mailtrap-password
   MAIL_ENCRYPTION=tls
   ```

### 4. SMTP Server Lain

Untuk SMTP server custom (cPanel, Office365, dll):

```env
MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

## Testing

### 1. Test Konfigurasi Email

Jalankan command Laravel untuk test email:

```bash
php artisan tinker
```

Kemudian di Tinker:

```php
Mail::raw('Test email', function ($message) {
    $message->to('test@example.com')
            ->subject('Test Email dari Sistem Absensi');
});
```

### 2. Test Forgot Password

1. **Buka halaman login**: `http://localhost/login`
2. **Klik "Lupa password?"**
3. **Input email guru/siswa** yang terdaftar
4. **Klik "Kirim Link Reset"**
5. **Cek email** (atau Mailtrap inbox jika development)
6. **Klik link di email**
7. **Input password baru**
8. **Login dengan password baru**

### 3. Test Rate Limiting

Coba kirim reset request lebih dari 3 kali dalam 1 jam untuk email yang sama. Seharusnya muncul error rate limit.

### 4. Test Token Expiration

1. Request reset password
2. Tunggu lebih dari 60 menit
3. Coba gunakan link - seharusnya invalid

### 5. Test Admin/TU Restriction

1. Coba request reset dengan email admin/tata usaha
2. Seharusnya redirect ke halaman contact-admin

## File-file yang Terlibat

### Backend
- `app/Http/Controllers/Auth/PasswordResetController.php` - Controller utama
- `app/Notifications/ResetPasswordNotification.php` - Custom email notification
- `app/Models/User.php` - Override sendPasswordResetNotification
- `routes/web.php` - Routes untuk password reset

### Frontend Views
- `resources/views/auth/passwords/email.blade.php` - Form request reset
- `resources/views/auth/passwords/reset.blade.php` - Form reset password
- `resources/views/auth/passwords/contact-admin.blade.php` - Instruksi untuk admin
- `resources/views/auth/login.blade.php` - Link "Lupa password"

### Database
- Migration `password_reset_tokens` sudah ada (bawaan Laravel)

## Security Features

### 1. Rate Limiting
- Maksimal 3 request per jam per kombinasi IP + email
- Mencegah brute force attack
- Error message tidak leak informasi sensitif

### 2. Email Enumeration Prevention
- Pesan sukses sama untuk email terdaftar atau tidak
- Mencegah attacker mengetahui email yang terdaftar
- Generic message: "Jika email terdaftar, link telah dikirim"

### 3. Token Security
- Token random 60 karakter
- Kedaluwarsa setelah 60 menit (configurable)
- Sekali pakai - invalid setelah digunakan
- Disimpan di database dengan hash

### 4. Password Validation
- Minimal 8 karakter
- Harus ada huruf besar
- Harus ada huruf kecil
- Harus ada angka
- Real-time strength indicator di UI

### 5. Admin Protection
- Email admin/tata usaha tidak bisa reset via email
- Redirect ke halaman contact admin
- Mencegah unauthorized access

## Troubleshooting

### Email tidak terkirim

**Problem**: Link reset tidak sampai ke inbox

**Solusi**:
1. Cek `.env` - pastikan MAIL_* sudah benar
2. Cek logs: `storage/logs/laravel.log`
3. Test koneksi SMTP:
   ```bash
   php artisan tinker
   Mail::raw('test', fn($m) => $m->to('test@example.com')->subject('Test'));
   ```
4. Cek spam folder
5. Pastikan firewall tidak block port SMTP (587/465)

### Token invalid atau expired

**Problem**: "This password reset token is invalid"

**Solusi**:
1. Token berlaku 60 menit - request ulang
2. Token sekali pakai - jangan gunakan link yang sama 2x
3. Clear browser cache
4. Pastikan email di URL sama dengan yang di-input

### Rate limit terlalu ketat

**Problem**: User di-block terlalu cepat

**Solusi**:
Edit `PasswordResetController.php`, ubah limit:
```php
// Dari 3 menjadi 5 request per jam
if (RateLimiter::tooManyAttempts($key, 5)) {
    // ...
}
RateLimiter::hit($key, 3600); // tetap 1 jam
```

### Admin masih bisa request reset

**Problem**: Admin bypass restriction

**Solusi**:
Cek role detection di `PasswordResetController::sendResetLinkEmail()`:
```php
if ($user && in_array($user->role, ['admin', 'tata_usaha'])) {
    return redirect()->route('password.contact-admin')...
}
```

## Customization

### 1. Ubah Durasi Token

Edit `config/auth.php`:

```php
'passwords' => [
    'users' => [
        'provider' => 'users',
        'table' => 'password_reset_tokens',
        'expire' => 120, // Ubah dari 60 menjadi 120 menit
        'throttle' => 60,
    ],
],
```

### 2. Ubah Rate Limit

Edit `PasswordResetController.php`:

```php
// Ganti 3 request menjadi 5, dan 3600 detik (1 jam) menjadi 7200 (2 jam)
if (RateLimiter::tooManyAttempts($key, 5)) {
    // ...
}
RateLimiter::hit($key, 7200);
```

### 3. Customize Email Template

Edit `app/Notifications/ResetPasswordNotification.php`:

```php
return (new MailMessage)
    ->subject('Custom Subject')
    ->line('Custom content here')
    ->action('Custom Button Text', $url)
    ->line('Custom footer');
```

### 4. Ubah Password Rules

Edit `PasswordResetController.php`:

```php
$request->validate([
    // ...
    'password' => [
        'required',
        'confirmed',
        'min:10', // Ubah minimal karakter
        'regex:/[a-z]/',
        'regex:/[A-Z]/',
        'regex:/[0-9]/',
        'regex:/[@$!%*?&#]/', // Tambah special char requirement
    ],
]);
```

## Production Checklist

- [ ] Update MAIL_* variables di `.env` production
- [ ] Test email delivery di production server
- [ ] Set proper MAIL_FROM_ADDRESS dengan domain sekolah
- [ ] Pastikan firewall tidak block port SMTP
- [ ] Test rate limiting
- [ ] Test dengan email guru & siswa real
- [ ] Verifikasi admin tidak bisa reset via email
- [ ] Monitor logs untuk error email
- [ ] Setup email monitoring/alerts
- [ ] Document SMTP credentials untuk team

## Support

Jika ada masalah:

1. **Check logs**: `storage/logs/laravel.log`
2. **Test SMTP**: Gunakan `php artisan tinker` untuk manual test
3. **Verify .env**: Pastikan semua MAIL_* sudah benar
4. **Contact administrator**: Jika masalah persist

## Changelog

### v1.0.0 (2024-06-16)
- ✨ Initial implementation
- ✨ Rate limiting (3 req/hour)
- ✨ Custom email notification
- ✨ Admin/TU restriction
- ✨ Password strength indicator
- ✨ Email enumeration prevention
- ✨ Token expiration (60 min)
- ✨ One-time use token

# Setup Storage Public di Production

## Struktur Folder di Hosting

```
/home/sitexamy/
├── public_html/
│   ├── app/
│   ├── config/
│   ├── resources/
│   ├── routes/
│   ├── .env
│   ├── artisan
│   └── storage_public/        ← Folder untuk foto profil (harus ada!)
│       ├── photos/
│       │   ├── students/
│       │   └── teachers/
│       └── .htaccess
```

## Langkah Setup

### 1. Buat Folder storage_public (Jika Belum Ada)

```bash
cd /home/sitexamy/public_html
mkdir -p storage_public/photos/students
mkdir -p storage_public/photos/teachers
chmod -R 755 storage_public
```

### 2. Buat .htaccess di storage_public

```bash
cat > storage_public/.htaccess << 'EOF'
<IfModule mod_rewrite.c>
    RewriteEngine Off
</IfModule>

<IfModule mod_negotiation.c>
    Options -MultiViews
</IfModule>

# Allow access to all files
<FilesMatch ".*">
    Require all granted
</FilesMatch>

# Set proper MIME types for images
<IfModule mod_mime.c>
    AddType image/jpeg .jpg .jpeg
    AddType image/png .png
    AddType image/gif .gif
    AddType image/webp .webp
</IfModule>
EOF
```

### 3. Set Permissions

```bash
cd /home/sitexamy/public_html
chmod 755 storage_public
chmod 755 storage_public/photos
chmod 755 storage_public/photos/students
chmod 755 storage_public/photos/teachers
chmod 644 storage_public/.htaccess
```

### 4. Update .env

```bash
nano .env
```

Pastikan ada baris ini:

```env
APP_URL=https://sitexa.my.id
STORAGE_PUBLIC_PATH=/home/sitexamy/public_html/storage_public
```

### 5. Clear Cache dan Test

```bash
# Clear semua cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Re-cache config (PENTING!)
php artisan config:cache

# Test URL generation
php artisan tinker
```

Di tinker, test:

```php
// Test config loaded correctly
config('filesystems.storage_public_path');
// Output: "/home/sitexamy/public_html/storage_public"

// Test URL generation
\App\Support\PublicStorageUrl::storageUrl('photos/students/test.jpg');
// Output: "https://sitexa.my.id/storage_public/photos/students/test.jpg"

// Test public directory
\App\Support\PublicStorageUrl::publicDirectory();
// Output: "storage_public"

exit
```

## Verifikasi Setup

### 1. Cek Struktur Folder

```bash
ls -la /home/sitexamy/public_html/storage_public/
```

Output yang benar:

```
drwxr-xr-x  3 sitexamy sitexamy 4096 Jun 17 15:00 .
drwxr-xr-x 15 sitexamy sitexamy 4096 Jun 17 14:00 ..
-rw-r--r--  1 sitexamy sitexamy  450 Jun 17 15:00 .htaccess
drwxr-xr-x  4 sitexamy sitexamy 4096 Jun 17 15:00 photos
```

### 2. Test Upload Photo

Di browser, coba upload foto profil siswa atau guru. Setelah upload, cek:

```bash
ls -la /home/sitexamy/public_html/storage_public/photos/students/
```

Harus muncul file foto yang baru diupload.

### 3. Test Akses Langsung via Browser

Buka URL foto di browser:

```
https://sitexa.my.id/storage_public/photos/students/nama_file.jpg
```

Harus menampilkan foto, bukan 404.

## Troubleshooting

### ❌ Problem: Foto tidak muncul setelah config:cache

**Penyebab:** Config cache menggunakan `env()` langsung (tidak work)

**Solusi:** Sudah diperbaiki! File `PublicStorageUrl.php` menggunakan `config()` dengan fallback `env()`

```php
// ❌ SALAH (tidak work setelah cache)
$path = env('STORAGE_PUBLIC_PATH');

// ✅ BENAR (work dengan atau tanpa cache)
$path = config('filesystems.storage_public_path') ?? env('STORAGE_PUBLIC_PATH');
```

### ❌ Problem: 403 Forbidden saat akses foto

**Penyebab:** Permissions salah

**Solusi:**

```bash
cd /home/sitexamy/public_html
chmod -R 755 storage_public
chmod 644 storage_public/.htaccess
```

### ❌ Problem: 404 Not Found saat akses foto

**Penyebab:** Folder belum dibuat atau path salah di .env

**Solusi:**

1. Cek folder ada:
   ```bash
   ls -la /home/sitexamy/public_html/storage_public
   ```

2. Cek .env setting benar:
   ```bash
   grep STORAGE_PUBLIC_PATH .env
   ```
   Output: `STORAGE_PUBLIC_PATH=/home/sitexamy/public_html/storage_public`

3. Cek config loaded:
   ```bash
   php artisan tinker
   config('filesystems.storage_public_path');
   exit
   ```

### ❌ Problem: Foto lama hilang setelah deploy

**Penyebab:** Git tidak track file binary (foto)

**Solusi:** Foto disimpan di server, tidak di Git. Backup manual jika perlu.

## Testing Checklist

Setelah setup, test semua ini:

- [ ] Folder `/home/sitexamy/public_html/storage_public` ada
- [ ] Folder `/home/sitexamy/public_html/storage_public/photos/students` ada
- [ ] Folder `/home/sitexamy/public_html/storage_public/photos/teachers` ada
- [ ] File `.htaccess` ada di `storage_public/`
- [ ] Permissions benar (755 untuk folder, 644 untuk .htaccess)
- [ ] `.env` ada `STORAGE_PUBLIC_PATH=/home/sitexamy/public_html/storage_public`
- [ ] Config cache: `php artisan config:cache` tanpa error
- [ ] Test di tinker: `config('filesystems.storage_public_path')` return path yang benar
- [ ] Test di tinker: `\App\Support\PublicStorageUrl::storageUrl('test.jpg')` return URL yang benar
- [ ] Upload foto siswa → muncul di halaman
- [ ] Upload foto guru → muncul di halaman
- [ ] Akses langsung URL foto → foto tampil (bukan 404)
- [ ] Run `php artisan config:cache` → foto tetap muncul

## File yang Sudah Diperbaiki

✅ `config/filesystems.php` - Menyimpan `STORAGE_PUBLIC_PATH` di config array
✅ `app/Support/PublicStorageUrl.php` - Gunakan `config()` dengan fallback `env()`
✅ `app/Models/User.php` - Gunakan `PublicStorageUrl::storageUrl()` untuk accessor
✅ `app/Models/Student.php` - Gunakan `PublicStorageUrl::storageUrl()` untuk accessor
✅ `app/Models/Teacher.php` - Gunakan `PublicStorageUrl::storageUrl()` untuk accessor

## Cara Kerja

1. **Development (localhost):**
   - `STORAGE_PUBLIC_PATH` kosong atau tidak ada
   - Foto disimpan di `storage/app/public/`
   - URL: `http://localhost/storage/photos/students/foto.jpg`

2. **Production (hosting):**
   - `STORAGE_PUBLIC_PATH=/home/sitexamy/public_html/storage_public`
   - Foto disimpan di `/home/sitexamy/public_html/storage_public/photos/`
   - URL: `https://sitexa.my.id/storage_public/photos/students/foto.jpg`

3. **Config Cache:**
   - `config:cache` membaca `.env` sekali, simpan ke file
   - `env()` tidak work setelah cache
   - `config()` tetap work karena baca dari cached file
   - `PublicStorageUrl` gunakan `config()` ✅

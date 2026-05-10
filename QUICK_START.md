# 🚀 Quick Start Guide - Setup Dashboard

Panduan cepat untuk menjalankan dashboard admin.

## ⚡ Setup Pertama Kali (5 Menit)

### 1. Buka PowerShell / Terminal

```powershell
# Windows + R, ketik: powershell
# Atau gunakan VS Code Terminal (Ctrl + `)
```

### 2. Masuk ke Folder Project

```powershell
cd "c:\SI Absensi Texmaco\si_absensi_texmaco"
```

### 3. Install Dependencies

```powershell
# Install PHP packages (Composer)
composer install

# Install Node packages (npm)
npm install
```

### 4. Setup Environment

```powershell
# Copy .env file
cp .env.example .env

# Generate app key
php artisan key:generate
```

### 5. Verifikasi Setup

```powershell
# Cek PHP version
php --version

# Cek Node version
node --version

# Cek npm version
npm --version
```

---

## 🏃 Run Development Dashboard

### Terminal 1 - Start Laravel Server

```powershell
cd "c:\SI Absensi Texmaco\si_absensi_texmaco"
php artisan serve
```

Output akan seperti:

```
INFO  Server running on [http://127.0.0.1:8000].

Press Ctrl+C to stop the server
```

**Akses**: Buka browser → `http://localhost:8000`

### Terminal 2 - Start Vite Dev Server

Buka terminal baru:

```powershell
cd "c:\SI Absensi Texmaco\si_absensi_texmaco"
npm run dev
```

Output akan seperti:

```
VITE v5.x.x  ready in xxx ms

➜  Local:   http://localhost:5173/
```

---

## 📋 Checklist Routes

Akses halaman-halaman ini di browser:

```
✅ http://localhost:8000/            → Dashboard
✅ http://localhost:8000/login       → Login page
✅ http://localhost:8000/siswa       → Data Siswa
✅ http://localhost:8000/guru        → Data Guru
✅ http://localhost:8000/jadwal      → Jadwal Kelas
✅ http://localhost:8000/monitoring/nfc    → NFC Monitor
✅ http://localhost:8000/request-izin-sakit → Requests
✅ http://localhost:8000/laporan/absensi  → Reports
✅ http://localhost:8000/alat-nfc   → Device Monitoring
✅ http://localhost:8000/settings   → Settings
```

---

## 🆘 Troubleshooting

### Error: "php artisan command not found"

```powershell
# Cek apakah PHP installed
php --version

# Jika tidak, install PHP dari:
# https://windows.php.net/download/
```

### Error: "npm: command not found"

```powershell
# Install Node.js dari:
# https://nodejs.org/

# Atau gunakan Chocolatey:
# choco install nodejs
```

### Error: "Composer packages missing"

```powershell
# Reinstall composer packages
composer install --no-interaction
```

### Kompilasi Tailwind error

```powershell
# Clear cache
npm run build

# Atau reinstall npm packages
rm -r node_modules
npm install
npm run build
```

### Port 8000 sudah terpakai

```powershell
# Gunakan port berbeda
php artisan serve --port=8001
# Akses: http://localhost:8001
```

---

## 🎨 CSS/JS Tidak Muncul

### Solusi 1: Compile Assets

```powershell
npm run dev
```

### Solusi 2: Clear Cache

```powershell
php artisan view:clear
php artisan cache:clear
```

### Solusi 3: Full Rebuild

```powershell
npm run build
php artisan optimize:clear
```

---

## 📁 File Penting

| File                    | Fungsi                |
| ----------------------- | --------------------- |
| `.env`                  | Environment variables |
| `tailwind.config.js`    | Tailwind CSS config   |
| `vite.config.js`        | Vite build config     |
| `resources/css/app.css` | Global styles         |
| `resources/js/app.js`   | JS entry point        |
| `resources/views/`      | Blade templates       |
| `routes/web.php`        | Route definitions     |

---

## 💡 Tips & Tricks

### Auto-format Code

```powershell
npm run format
```

### Run Tests

```powershell
php artisan test
```

### Database Migration

```powershell
php artisan migrate
```

### View Routes

```powershell
php artisan route:list
```

---

## 🌐 Production Build

Ketika siap deploy:

```powershell
npm run build
php artisan config:cache
php artisan optimize
```

---

## 📞 Bantuan Lebih Lanjut

Lihat dokumentasi lengkap:

- `INSTALLATION_GUIDE.md` - Detail setup
- `COMPONENTS_GUIDE.md` - UI Components
- `DESIGN_SUMMARY.md` - Design overview
- `UI_UX_DOCUMENTATION.md` - Complete docs

---

**Happy Coding! 🎉**

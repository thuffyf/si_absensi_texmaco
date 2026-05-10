# 🚀 Installation & Setup Guide

Panduan lengkap untuk setup dan development Dashboard Admin Sistem Absensi NFC.

## 📋 Requirements

- **PHP**: >= 8.1
- **Node.js**: >= 16
- **npm**: >= 8
- **Laravel**: 10.x
- **Composer**: Latest

## 🔧 Installation Steps

### 1. Clone/Extract Project

```bash
cd "c:\SI Absensi Texmaco\si_absensi_texmaco"
```

### 2. Install Dependencies

#### Laravel Dependencies

```bash
composer install
```

#### Node Dependencies (Frontend)

```bash
npm install
```

### 3. Environment Setup

```bash
# Copy .env file
cp .env.example .env

# Generate app key
php artisan key:generate

# Setup database (jika digunakan)
php artisan migrate
```

### 4. Install & Configure Tailwind CSS

Sudah disertakan di `package.json`, tapi jika perlu reinstall:

```bash
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

File konfigurasi sudah tersedia:

- `tailwind.config.js` - Custom colors & theme
- `postcss.config.js` - PostCSS configuration

### 5. Build Assets

#### Development Mode (with Hot Reload)

```bash
npm run dev
```

Atau di terminal terpisah:

```bash
php artisan serve
```

Akses: `http://localhost:8000`

#### Production Build

```bash
npm run build
```

---

## 📁 Project Structure

```
si_absensi_texmaco/
├── resources/
│   ├── css/
│   │   └── app.css              # Tailwind directives + custom styles
│   ├── js/
│   │   ├── app.js               # Entry point
│   │   ├── bootstrap.js         # Bootstrap with Axios
│   │   └── dashboard.js         # Dashboard interactions
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php    # Master layout
│       ├── auth/
│       │   └── login.blade.php
│       ├── dashboard/
│       ├── monitoring/
│       ├── students/
│       ├── teachers/
│       ├── schedules/
│       ├── requests/
│       ├── reports/
│       ├── devices/
│       └── settings/
├── routes/
│   └── web.php                  # Web routes
├── config/
│   └── app.php                  # App configuration
├── tailwind.config.js           # Tailwind configuration
├── postcss.config.js            # PostCSS configuration
├── vite.config.js               # Vite configuration
├── package.json                 # Node dependencies
└── composer.json                # PHP dependencies
```

---

## 🎨 Development Workflow

### Running Development Server

#### Terminal 1 - Laravel Server

```bash
php artisan serve
```

Output: `Local: http://127.0.0.1:8000`

#### Terminal 2 - Vite Dev Server

```bash
npm run dev
```

Output: `Local: http://localhost:5173`

### File Structure for Development

**CSS Changes:**

1. Edit `resources/css/app.css`
2. Changes auto-compile via Vite
3. Browser auto-refresh

**JavaScript Changes:**

1. Edit `resources/js/dashboard.js`
2. Changes auto-compile
3. Browser auto-refresh

**View Changes:**

1. Edit Blade files in `resources/views/`
2. Browser auto-refresh (if Laravel refresh enabled)

---

## 🎯 Available Routes

Setelah setup, routes berikut tersedia:

```
GET  /                      → Dashboard (login required)
GET  /login                 → Login page
GET  /dashboard             → Dashboard utama
GET  /monitoring/nfc        → NFC real-time monitoring
GET  /siswa                 → Data siswa
GET  /guru                  → Data guru
GET  /jadwal                → Jadwal kelas
GET  /request-izin-sakit    → Request izin/sakit
GET  /laporan/absensi       → Laporan absensi
GET  /alat-nfc              → Monitoring alat NFC
GET  /settings              → Pengaturan sistem
```

---

## 📦 NPM Scripts

```bash
npm run dev          # Run development server dengan hot reload
npm run build        # Build untuk production
npm run lint         # Lint JavaScript (jika ESLint configured)
```

---

## 🌐 Accessing the Dashboard

### Development

1. Buka `http://localhost:8000`
2. Halaman login akan muncul
3. Untuk testing, langsung akses halaman:
    - `http://localhost:8000/dashboard`
    - `http://localhost:8000/siswa`
    - dll.

### Login Credentials (Testing)

Username: `admin@texmaco.id`
Password: `password`

_Note: Authentication system belum fully implemented_

---

## 🎨 Customizing Colors & Styles

### Change Primary Color

Edit `tailwind.config.js`:

```javascript
colors: {
    'neon-cyan': '#00FF00',  // Change cyan to green
    'neon-purple': '#FF00FF',
    // ... other colors
}
```

Then rebuild:

```bash
npm run build
```

### Add Custom CSS Classes

Edit `resources/css/app.css`:

```css
.my-custom-class {
    @apply glass-card p-6 rounded-2xl;
    /* additional styles */
}
```

### Modify Tailwind Config

Edit `tailwind.config.js`:

```javascript
export default {
    content: ["./resources/views/**/*.blade.php", "./resources/js/**/*.js"],
    theme: {
        extend: {
            // Add extensions here
        },
    },
};
```

---

## 🔍 Troubleshooting

### Issue: CSS not applying

**Solution:**

```bash
# Clear cache
php artisan view:clear
php artisan config:clear

# Rebuild assets
npm run build
```

### Issue: Vite not connecting

**Solution:**

1. Make sure both servers running
2. Check port 5173 is not in use
3. Restart `npm run dev`

### Issue: Hot reload not working

**Solution:**

1. Check `vite.config.js` HMR config
2. Make sure dev server running
3. Hard refresh browser (Ctrl+Shift+R)

### Issue: Tailwind classes not showing

**Solution:**

```bash
# Ensure tailwind content paths are correct
# in tailwind.config.js

# Rebuild
npm run build

# Clear browser cache
```

---

## 📝 Blade Template Tips

### Using Tailwind in Blade

```blade
<div class="glass-card p-6 rounded-2xl border border-neon-cyan/20">
    {{ $content }}
</div>
```

### Using Vite in Blade

```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

### Yielding Sections

```blade
<!-- In layout -->
@yield('content')

<!-- In view -->
@section('content')
    <div>Page content</div>
@endsection
```

---

## 🚀 Production Deployment

### Build for Production

```bash
npm run build
```

### Deployment Steps

1. **Build assets:**

    ```bash
    npm run build
    ```

2. **Clear caches:**

    ```bash
    php artisan config:cache
    php artisan view:cache
    ```

3. **Set permissions:**

    ```bash
    chmod -R 755 storage/
    chmod -R 755 bootstrap/cache/
    ```

4. **Setup web server:**
    - Point document root to `public/`
    - Configure `.env` for production
    - Setup database

---

## 🔐 Security Considerations

1. **Never commit `.env`** - Already in `.gitignore`
2. **Use HTTPS** in production
3. **Validate all inputs** in routes/controllers
4. **Sanitize outputs** in views
5. **Use CSRF tokens** for forms
6. **Rate limit API endpoints**

---

## 📚 Learning Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS Docs](https://tailwindcss.com/docs)
- [Vite Documentation](https://vitejs.dev)
- [MDN Web Docs](https://developer.mozilla.org)

---

## 🆘 Getting Help

### Check Logs

```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Browser console
F12 → Console tab
```

### Common Issues Location

- Frontend issues → Browser DevTools (F12)
- Backend issues → `storage/logs/laravel.log`
- Styling issues → Browser Inspector (F12 → Elements)

---

## ✅ Checklist Sebelum Production

- [ ] All routes tested
- [ ] Responsive design verified (mobile, tablet, desktop)
- [ ] Performance optimized
- [ ] Security headers set
- [ ] Database seeded with test data
- [ ] API documentation ready
- [ ] Error pages customized
- [ ] Logging configured
- [ ] Backup strategy in place
- [ ] SSL certificate installed

---

## 📞 Support

Untuk bantuan lebih lanjut atau pertanyaan:

- Lihat dokumentasi: `UI_UX_DOCUMENTATION.md`
- Lihat komponen: `COMPONENTS_GUIDE.md`
- Check GitHub issues atau dokumentasi Laravel

---

**Happy Coding! 🚀**

Created for Sistem Absensi NFC Texmaco School
Version 1.0.0 | 2024

# 📱 Admin Dashboard UI/UX - Sistem Absensi NFC

Dashboard Admin modern dan futuristic untuk Sistem Absensi Sekolah berbasis NFC dengan desain **Glassmorphism**, tema **Dark Blue**, **Neon Cyan**, dan responsif untuk semua perangkat.

## 🎨 Desain & Fitur Utama

### Tema Desain

- **Glassmorphism Effect**: UI dengan efek kaca buram yang elegan
- **Dark Mode**: Tema gelap dengan dark blue (#0F0F1E) sebagai background
- **Neon Cyan**: Warna aksen utama (#00D9FF)
- **Modern & Professional**: Inspirasi dari Notion, Discord, Linear App

### Teknologi yang Digunakan

- **Framework**: Laravel + Vite
- **Styling**: Tailwind CSS 3
- **Animasi**: CSS Animations & Transitions
- **JavaScript**: Vanilla JS untuk interaksi real-time

## 📂 Struktur File

### CSS & Configuration

```
resources/css/
├── app.css                 # Tailwind directives + custom styles
resources/
├── tailwind.config.js      # Konfigurasi Tailwind dengan custom colors
└── postcss.config.js       # PostCSS configuration
```

### Views & Pages

```
resources/views/
├── layouts/
│   └── app.blade.php          # Master layout dengan sidebar & navbar
├── auth/
│   └── login.blade.php        # Login page modern
├── dashboard/
│   └── index.blade.php        # Dashboard utama dengan stats & monitoring
├── monitoring/
│   └── nfc.blade.php          # NFC Real-time monitoring
├── students/
│   └── index.blade.php        # Data siswa dengan table modern
├── teachers/
│   └── index.blade.php        # Data guru dengan grid cards
├── schedules/
│   └── index.blade.php        # Jadwal kelas dan monitoring
├── requests/
│   └── izin-sakit.blade.php   # Request izin/sakit dengan approval
├── reports/
│   └── absensi.blade.php      # Laporan absensi dengan grafik
├── devices/
│   └── nfc-tools.blade.php    # Monitoring alat NFC
└── settings/
    └── index.blade.php        # Pengaturan sistem
```

### JavaScript

```
resources/js/
├── app.js          # Entry point
├── bootstrap.js    # Bootstrap dengan Axios
└── dashboard.js    # Dashboard interactions & real-time features
```

## 🎯 Halaman-Halaman

### 1. **Login Admin** (`resources/views/auth/login.blade.php`)

- Form login dengan glassmorphism effect
- Animasi background gradient
- Biometric login option
- Styling elegan dan modern

### 2. **Dashboard Utama** (`resources/views/dashboard/index.blade.php`)

- Statistik siswa (Hadir, Izin, Sakit, Alpha)
- Statistik scan NFC (Berhasil, Gagal, Tidak Terdaftar)
- Grafik absensi harian dan mingguan
- Real-time monitoring tap-in siswa
- Status alat NFC dengan indikator online/offline
- Cards dengan glow effects dan hover animations

### 3. **NFC Real-Time Monitor** (`resources/views/monitoring/nfc.blade.php`)

- Event stream live dari scan NFC
- Filter dan search untuk scan events
- Visualization dengan scanning animation
- Status perangkat dengan real-time indicators
- Detail scan: Nama siswa, UID, Waktu, Status

### 4. **Data Siswa** (`resources/views/students/index.blade.php`)

- Tabel modern dengan glassmorphism
- Search, filter, edit, delete, detail actions
- Foto profile siswa dengan gradient avatars
- Info: NIM, Kelas, Jurusan, Status, NFC
- Pagination dengan styling modern

### 5. **Data Guru** (`resources/views/teachers/index.blade.php`)

- Grid card layout untuk guru
- Informasi: Nama, NIP, Mata Pelajaran, Email
- Statistik kehadiran dan jam mengajar
- Status (Aktif, Cuti, Non-Aktif)
- Quick actions: Edit, Detail, Delete

### 6. **Jadwal Kelas** (`resources/views/schedules/index.blade.php`)

- Jadwal per hari dengan layout terstruktur
- Informasi: Jam, Kelas, Mata Pelajaran, Guru
- Indikator jadwal yang sedang berlangsung
- Real-time attendance tracking
- Monitoring kelas aktif

### 7. **Request Izin & Sakit** (`resources/views/requests/izin-sakit.blade.php`)

- Tab filter: Semua, Tertunda, Disetujui, Ditolak
- Card untuk setiap request dengan detail lengkap
- Tombol Terima/Tolak untuk verifikasi
- Riwayat pengajuan (Disetujui & Ditolak)
- Visual status badges dengan animasi

### 8. **Laporan Absensi** (`resources/views/reports/absensi.blade.php`)

- Filter laporan: Tanggal, Kelas, Status
- Grafik absensi harian
- Perbandingan kehadiran per kelas
- Tabel detail absensi per siswa
- Statistik: Kehadiran terbaik, perlu perhatian, summary

### 9. **Monitoring Alat NFC** (`resources/views/devices/nfc-tools.blade.php`)

- Grid device cards dengan status
- Info: IP, Uptime, Last Check, Scan count
- Status indicators: Online, Idle, Offline (dengan animasi)
- Riwayat aktivitas/logs perangkat
- Actions: Edit, Detail, Alert

### 10. **Pengaturan Sistem** (`resources/views/settings/index.blade.php`)

- Informasi sekolah (NPSN, Alamat, Kontak)
- Konfigurasi sistem (Jam masuk, Toleransi, Threshold)
- Notifikasi & Alert settings
- Display settings (Tema, Font, Animasi)
- Data management & backup
- Informasi system

## 🎨 Custom Colors & Styles

### Tailwind Configuration

```javascript
// tailwind.config.js
colors: {
  'neon-cyan': '#00D9FF',
  'neon-purple': '#B537F2',
  'neon-blue': '#0066FF',
  'dark-bg': '#0F0F1E',
  'dark-card': '#1A1A2E',
  'dark-border': '#16213E',
}

boxShadow: {
  'glow-cyan': '0 0 20px rgba(0, 217, 255, 0.3)',
  'glow-cyan-sm': '0 0 10px rgba(0, 217, 255, 0.2)',
  'glow-cyan-lg': '0 0 30px rgba(0, 217, 255, 0.4)',
  'glass': '0 8px 32px rgba(0, 0, 0, 0.3)',
}
```

### Custom Classes (CSS)

- `.glass-effect` - Glassmorphism dengan backdrop blur
- `.glass-card` - Kartu dengan efek kaca
- `.btn-primary` - Tombol utama dengan gradient
- `.btn-secondary` - Tombol sekunder dengan efek glass
- `.stat-card` - Kartu statistik dengan hover effect
- `.badge-*` - Status badges (success, warning, danger, info)
- `.neon-text` - Teks dengan animasi neon flicker

### Animasi

- `animate-pulse-glow` - Pulse dengan glow effect
- `animate-float` - Float up-down animation
- `animate-slide-in` - Slide dari kiri
- `animate-fade-in` - Fade in dari transparent
- `animate-scan-pulse` - Scanning animation

## 🚀 Setup & Installation

### 1. Install Dependencies

```bash
cd si_absensi_texmaco
npm install -D tailwindcss postcss autoprefixer
```

### 2. Build CSS

```bash
npm run dev
# atau untuk production:
npm run build
```

### 3. Jalankan Laravel

```bash
php artisan serve
# atau gunakan Vite:
npm run dev
```

## 📝 Fitur Responsif

- ✅ **Mobile**: Full responsive untuk mobile dengan drawer sidebar
- ✅ **Tablet**: Layout optimized untuk tablet
- ✅ **Desktop**: Full featured view untuk desktop
- ✅ **Custom Breakpoints**: `sm`, `md`, `lg` Tailwind defaults

## 🔧 Interaksi & JavaScript

File `resources/js/dashboard.js` menangani:

- Navigation dan page switching
- Real-time monitoring simulation
- Animations on scroll
- Notification system
- Toast messages
- Button actions (Accept/Reject requests)

## 🎯 Best Practices yang Diterapkan

✨ **Glassmorphism**

- Backdrop blur untuk depth
- Semi-transparent backgrounds
- Soft shadows untuk dimension

💫 **Animasi**

- Smooth transitions
- Micro-interactions
- Loading states
- Hover effects

🎨 **Color Consistency**

- Dark theme yang konsisten
- Neon accents untuk CTA
- Semantic color usage (Success=Green, Error=Red)

📱 **Responsiveness**

- Mobile-first approach
- Grid layouts yang fleksibel
- Touch-friendly interactions

♿ **Accessibility**

- Semantic HTML
- Color contrast yang baik
- Clear visual indicators
- Proper heading hierarchy

## 📊 Komponen yang Dapat Digunakan Kembali

```blade
<!-- Stat Card -->
<div class="stat-card">
    <p class="stat-label">Label</p>
    <div class="stat-number">123</div>
</div>

<!-- Status Badge -->
<span class="badge-success">Status</span>

<!-- Glass Card -->
<div class="glass-card p-6">
    <!-- Content -->
</div>

<!-- Button Styles -->
<button class="btn-primary">Primary</button>
<button class="btn-secondary">Secondary</button>
<button class="btn-danger">Danger</button>
```

## 🔮 Pengembangan Lebih Lanjut

Untuk production, tambahkan:

- [ ] WebSocket untuk real-time updates
- [ ] Authentication system
- [ ] API endpoints
- [ ] Database integration
- [ ] File upload for photos
- [ ] Export to PDF/Excel
- [ ] Dark/Light mode toggle
- [ ] Multi-language support

## 📄 Lisensi

Desain UI/UX ini dibuat untuk Sistem Absensi NFC Texmaco School.

---

**Created with ✨ Modern Dashboard Design**
Glassmorphism + Dark Theme + Neon Cyan Accent

# 🎨 UI Components Library - Admin Dashboard

Dokumentasi lengkap semua komponen UI yang tersedia di dashboard admin.

## 📦 Komponen Dasar

### 1. **Glass Card** (Glassmorphism Container)

Kontainer dengan efek kaca buram yang elegan, cocok untuk semua tipe konten.

```html
<div class="glass-card p-6 rounded-2xl">
    <!-- Content here -->
</div>
```

**Variasi:**

- `glass-card` - Standard glass effect dengan hover
- `glass-card-dark` - Darker glass effect
- `glass-effect` - Hanya efek tanpa padding
- `glass-dark-effect` - Darker glass effect saja

---

### 2. **Stat Card** (Statistik Utama)

Menampilkan statistik dengan angka besar dan label.

```html
<div class="stat-card">
    <p class="stat-label">Label</p>
    <div class="stat-number">123</div>
    <div class="stat-change positive">↑ 12 dari kemarin</div>
</div>
```

**Atribut:**

- `stat-number` - Nomor/value besar
- `stat-label` - Label deskriptif
- `stat-change` - Perubahan (positive/negative)

---

### 3. **Buttons** (Tombol)

#### Primary Button

```html
<button class="btn-primary">Action</button>
```

#### Secondary Button

```html
<button class="btn-secondary">Secondary Action</button>
```

#### Danger Button

```html
<button class="btn-danger">Delete</button>
```

#### Success Button

```html
<button class="btn-success">Approve</button>
```

#### Icon Button

```html
<button class="btn-icon">✏️</button>
```

---

### 4. **Badges/Status** (Indikator Status)

#### Success Badge

```html
<span class="badge-success">Hadir</span>
```

#### Warning Badge

```html
<span class="badge-warning">Izin</span>
```

#### Danger Badge

```html
<span class="badge-danger">Sakit</span>
```

#### Info Badge

```html
<span class="badge-info">Info</span>
```

#### Neon Badge (Cyan Glow)

```html
<span class="badge-neon">Kartu NFC</span>
```

---

### 5. **Input Fields** (Form Input)

#### Text Input

```html
<input type="text" class="input-field" placeholder="Cari..." />
```

#### Email Input

```html
<input type="email" class="input-field" placeholder="Email..." />
```

#### Password Input

```html
<input type="password" class="input-field" placeholder="Password..." />
```

#### Select/Dropdown

```html
<select class="input-field">
    <option>Option 1</option>
    <option>Option 2</option>
</select>
```

---

### 6. **Tables** (Data Table)

```html
<table class="data-table">
    <thead>
        <tr>
            <th>Column 1</th>
            <th>Column 2</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Data 1</td>
            <td>Data 2</td>
        </tr>
    </tbody>
</table>
```

**Fitur:**

- Hover effect pada baris
- Responsive design
- Glassmorphism styling

---

### 7. **Text Styles** (Teks)

#### Gradient Text

```html
<h1 class="text-gradient">Gradient Text</h1>
<h1 class="text-gradient-purple">Purple Gradient</h1>
```

#### Neon Text (Flicker Effect)

```html
<span class="neon-text">Neon Flicker</span>
```

---

## 🎭 Animasi & Efek

### Available Animations

- `animate-pulse-glow` - Pulse dengan glow
- `animate-float` - Float up-down
- `animate-glow-fade` - Fade glow effect
- `animate-slide-in` - Slide dari kiri
- `animate-fade-in` - Fade in
- `animate-scan-pulse` - Scanning animation

### Contoh Penggunaan

```html
<div class="animate-fade-in">Content</div>
<div class="animate-slide-in" style="animation-delay: 0.1s;">Delayed</div>
```

---

## 🌈 Color System

### Primary Colors

- **Neon Cyan**: `#00D9FF` - Main accent color
- **Neon Purple**: `#B537F2` - Secondary accent
- **Neon Blue**: `#0066FF` - Tertiary accent

### Background Colors

- **Dark BG**: `#0F0F1E` - Main background
- **Dark Card**: `#1A1A2E` - Card background
- **Dark Border**: `#16213E` - Border color

### Semantic Colors

- **Success**: Emerald/Green
- **Warning**: Yellow/Amber
- **Danger**: Red
- **Info**: Blue/Cyan

---

## 📐 Spacing & Sizing

### Padding

- `p-4` - 1rem (16px)
- `p-6` - 1.5rem (24px)
- `p-8` - 2rem (32px)

### Rounded Corners

- `rounded-lg` - 8px
- `rounded-xl` - 12px
- `rounded-2xl` - 16px
- `rounded-full` - 9999px

---

## 🔐 Layout Components

### Sidebar Navigation

```html
<aside class="fixed left-0 top-0 h-screen w-64 glass-effect">
    <nav>
        <a href="#" class="nav-item active">Item</a>
        <a href="#" class="nav-item">Item</a>
    </nav>
</aside>
```

### Top Navbar

```html
<nav class="fixed top-0 right-0 left-0 h-16 glass-effect">
    <!-- Navbar content -->
</nav>
```

### Main Content Area

```html
<main class="flex-1 lg:ml-64">
    <div class="pt-20 pb-8">
        <div class="px-6">
            <!-- Content -->
        </div>
    </div>
</main>
```

---

## 🎯 Layout Patterns

### Grid Layout (Responsive)

```html
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div>Item 1</div>
    <div>Item 2</div>
</div>
```

### Flex Layout

```html
<div class="flex items-center justify-between gap-4">
    <div>Left</div>
    <div>Right</div>
</div>
```

---

## 🚀 Advanced Components

### Modal / Dialog

```html
<div class="glass-card p-8 rounded-2xl max-w-md">
    <!-- Modal content -->
</div>
```

### Dropdown/Menu

```html
<button class="btn-secondary">Menu ▾</button>
<!-- Dropdown items -->
```

### Toast Notification

```javascript
window.DashboardUtils.showToast("Message", "success");
```

---

## 📱 Responsive Breakpoints

- **Mobile**: Default (< 640px)
- **Small (sm)**: 640px+
- **Medium (md)**: 768px+
- **Large (lg)**: 1024px+
- **XL (xl)**: 1280px+
- **2XL (2xl)**: 1536px+

### Contoh

```html
<div class="text-sm md:text-base lg:text-lg">Responsive text</div>
```

---

## 🎨 Custom Styling

### Adding Custom Classes

Tambahkan ke `resources/css/app.css`:

```css
.custom-component {
    @apply glass-card p-6 rounded-2xl border border-neon-cyan/20;
}
```

### Using Tailwind @apply

```css
.my-button {
    @apply px-4 py-2 rounded-lg font-semibold transition-all;
}
```

---

## ⚡ Performance Tips

1. **Minimize Animations**: Gunakan `prefers-reduced-motion`
2. **Lazy Load Images**: Gunakan `loading="lazy"`
3. **Optimize Shadows**: Use box-shadow sparingly
4. **Debounce Events**: Real-time monitoring events

---

## 🔧 Customization Guide

### Mengubah Warna Utama

Edit `tailwind.config.js`:

```javascript
colors: {
    'neon-cyan': '#00D9FF', // Ubah di sini
}
```

### Mengubah Font

```javascript
theme: {
    fontFamily: {
        sans: ['Segoe UI', 'Helvetica', ...],
    }
}
```

### Menambah Breakpoint

```javascript
screens: {
    'xs': '320px',
    // ... existing breakpoints
}
```

---

## 📚 Referensi External

- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [CSS Animations](https://developer.mozilla.org/en-US/docs/Web/CSS/animation)
- [Glassmorphism UI](https://uxdesign.cc/glassmorphism-in-user-interfaces-77f860d7f6e6)

---

**Dashboard UI Library v1.0.0**
Dibuat untuk Sistem Absensi NFC Texmaco School

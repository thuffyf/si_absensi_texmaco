<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - Sistem Absensi NFC Texmaco</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-slate-100 text-slate-900">
    <div id="app" class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="fixed left-0 top-0 h-screen w-64 bg-white border-r border-slate-200 hidden lg:flex flex-col z-50 shadow-sm">
            <!-- Logo -->
            <div class="p-6 border-b border-slate-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-500 to-blue-600 flex items-center justify-center text-lg font-bold text-white shadow-md">
                        ⚡
                    </div>
                    <div>
                        <h1 class="font-bold text-slate-900">NFC Admin</h1>
                        <p class="text-xs text-slate-500">Texmaco School</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <a href="#" class="nav-item-light active" data-page="dashboard">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 9l-5-5m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="nav-item-light" data-page="monitoring-nfc">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>NFC Monitor</span>
                </a>
                <a href="#" class="nav-item-light" data-page="siswa">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM16 16a5 5 0 010 10H4a5 5 0 010-10h12z"></path>
                    </svg>
                    <span>Data Siswa</span>
                </a>
                <a href="#" class="nav-item-light" data-page="guru">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17.25m20-11.002c2.3 1.713 3.8 4.75 3.8 8.002 0 5.591-4.445 10.269-9.8 10.269"></path>
                    </svg>
                    <span>Data Guru</span>
                </a>
                <a href="#" class="nav-item-light" data-page="jadwal">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>Jadwal</span>
                </a>
                <a href="#" class="nav-item-light" data-page="request-izin">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Izin & Sakit</span>
                </a>
                <a href="#" class="nav-item-light" data-page="laporan">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>Laporan</span>
                </a>
                <a href="#" class="nav-item-light" data-page="alat">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Alat NFC</span>
                </a>
                <a href="#" class="nav-item-light" data-page="settings">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Pengaturan</span>
                </a>
            </nav>

            <!-- Profile -->
            <div class="p-4 border-t border-slate-200">
                <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-100">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-sky-500 to-blue-600 flex items-center justify-center font-bold text-white">👨</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate text-slate-900">Admin TU</p>
                        <p class="text-xs text-slate-500 truncate">admin@texmaco.id</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 lg:ml-64">
            <!-- Top Navbar -->
            <nav class="fixed top-0 right-0 left-0 lg:left-64 h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 z-40 shadow-sm">
                <div class="flex items-center gap-4">
                    <!-- Mobile Menu Toggle -->
                    <button class="lg:hidden p-2 rounded-lg hover:bg-glass-light/20">
                        <svg class="w-6 h-6 text-neon-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h2 class="text-xl font-bold text-slate-900 hidden sm:block" id="page-title">Dashboard</h2>
                </div>

                <div class="flex items-center gap-4">
                    <!-- Search -->
                    <div class="hidden md:flex items-center">
                        <div class="relative">
                            <input type="text" placeholder="Cari..." class="w-48 rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 bg-slate-50 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100 placeholder-slate-400" />
                        </div>
                    </div>

                    <!-- Notifications -->
                    <button class="p-3 rounded-xl hover:bg-slate-100 transition-colors relative" id="notification-btn">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    </button>

                    <!-- User Menu -->
                    <div class="flex items-center gap-3 pl-4 border-l border-slate-200">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-semibold text-slate-900">Admin TU</p>
                            <p class="text-xs text-slate-500">Administrator</p>
                        </div>
                        <button class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-500 to-blue-600 flex items-center justify-center font-bold text-white shadow-md hover:shadow-lg transition-shadow">👨</button>
                    </div>
                </div>
            </nav>

            <!-- Content Area -->
            <div class="pt-20 pb-8">
                <div class="px-6" id="content-area">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <style>
        .nav-item-light {
            @apply flex items-center gap-3 px-4 py-3 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all duration-200 group;
        }
        
        .nav-item-light.active {
            @apply text-sky-600 bg-sky-50 border-r-4 border-sky-600;
        }
        
        .nav-item-light svg {
            @apply text-slate-400 group-hover:text-sky-600;
        }
        
        .nav-item-light.active svg {
            @apply text-sky-600;
        }

        .nav-item.active {
            @apply text-neon-cyan bg-neon-cyan/10 border-l-2 border-neon-cyan;
        }

        .nav-item svg {
            @apply w-5 h-5 group-hover:text-neon-cyan transition-colors;
        }

        .nav-item.active svg {
            @apply text-neon-cyan;
        }
    </style>
</body>
</html>

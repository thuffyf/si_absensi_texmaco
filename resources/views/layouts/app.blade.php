<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'SITEXA Absensi — Texmaco Purwasari')</title>
    @include('partials.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body @class(['min-h-screen text-slate-900 font-sans antialiased', 'lg:h-dvh lg:overflow-hidden' => request()->routeIs('dashboard')])>
    <!-- Latar gradasi (selaras login) -->
    <div class="pointer-events-none fixed inset-0 z-0 bg-gradient-to-br from-blue-50 via-slate-50 to-slate-100" aria-hidden="true"></div>

    <div id="layout-root" @class(['relative z-10 flex min-h-screen', 'lg:h-full lg:min-h-0' => request()->routeIs('dashboard')])>
        <!-- Backdrop mobile -->
        <div
            id="sidebar-backdrop"
            class="fixed inset-0 z-40 hidden bg-slate-900/35 backdrop-blur-[2px]"
            aria-hidden="true"
        ></div>

        <!-- Sidebar -->
        <aside
            id="admin-sidebar"
            class="sidebar-shell fixed left-0 top-0 z-50 flex h-screen w-64 flex-col border-r border-sky-100/80 bg-gradient-to-b from-sky-50/90 via-white to-slate-50/95 shadow-[4px_0_24px_-8px_rgba(15,23,42,0.08)] backdrop-blur-sm transition-[width,margin,transform] duration-200 ease-out -translate-x-full lg:translate-x-0"
        >
            <div class="relative flex shrink-0 items-center border-b border-sky-100/90 bg-white/60 px-3 py-3 backdrop-blur-sm">
                <button
                    type="button"
                    id="sidebar-toggle"
                    class="inline-flex rounded-xl p-2.5 text-slate-700 ring-1 ring-slate-200/80 hover:bg-white hover:ring-sky-200 focus:outline-none focus:ring-2 focus:ring-sky-300"
                    aria-controls="admin-sidebar"
                    aria-expanded="false"
                    aria-label="Buka atau tutup menu"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            @php $userRole = auth()->user()->role ?? 'tata_usaha'; @endphp
            <nav class="flex flex-1 flex-col gap-1 overflow-y-auto p-3 pt-4" aria-label="Menu utama">
                <a
                    href="{{ route('dashboard') }}"
                    title="Dashboard"
                    class="nav-texmaco border-b border-slate-200 {{ request()->routeIs('dashboard') ? 'nav-texmaco-active' : '' }}"
                >
                    <span class="nav-icon" aria-hidden="true">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h7v7H3V3zm0 11h7v7H3v-7zm11-11h7v7h-7V3zm0 11h7v7h-7v-7z" />
                        </svg>
                    </span>
                    <span class="nav-text">Dashboard</span>
                </a>

                @if($userRole === 'tata_usaha')
                    <a
                        href="{{ route('students.index') }}"
                        title="Data Siswa"
                        class="nav-texmaco border-b border-slate-200 {{ request()->routeIs('students.index') ? 'nav-texmaco-active' : '' }}"
                    >
                        <span class="nav-icon" aria-hidden="true">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6m9-6v6a9 9 0 11-18 0v-6" />
                            </svg>
                        </span>
                        <span class="nav-text">Data Siswa</span>
                    </a>
                    <a
                        href="{{ route('teachers.index') }}"
                        title="Data Guru"
                        class="nav-texmaco border-b border-slate-200 {{ request()->routeIs('teachers.index') ? 'nav-texmaco-active' : '' }}"
                    >
                        <span class="nav-icon" aria-hidden="true">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a7.5 7.5 0 0115 0" />
                            </svg>
                        </span>
                        <span class="nav-text">Data Guru</span>
                    </a>
                    <a
                        href="{{ route('schedules.index') }}"
                        title="Jadwal"
                        class="nav-texmaco border-b border-slate-200 {{ request()->routeIs('schedules.*') ? 'nav-texmaco-active' : '' }}"
                    >
                        <span class="nav-icon" aria-hidden="true">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </span>
                        <span class="nav-text">Jadwal</span>
                    </a>
                    <a
                        href="{{ route('absensi.index') }}"
                        title="Absensi"
                        class="nav-texmaco border-b border-slate-200 {{ request()->routeIs('absensi.*') ? 'nav-texmaco-active' : '' }}"
                    >
                        <span class="nav-icon" aria-hidden="true">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m-7-3h7.5a2.25 2.25 0 012.25 2.25v9a2.25 2.25 0 01-2.25 2.25h-9A2.25 2.25 0 014 18.25v-9A2.25 2.25 0 016.25 7h.5m7-3H9a2.25 2.25 0 00-2.25 2.25v.75h10.5V6.25A2.25 2.25 0 0015 4z" />
                            </svg>
                        </span>
                        <span class="nav-text">Absensi</span>
                    </a>
                    <a
                        href="{{ route('monitoring.nfc') }}"
                        title="Monitoring NFC"
                        class="nav-texmaco border-b border-slate-200 {{ request()->routeIs('monitoring.nfc') ? 'nav-texmaco-active' : '' }}"
                    >
                        <span class="nav-icon" aria-hidden="true">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                            </svg>
                        </span>
                        <span class="nav-text">Monitoring NFC</span>
                    </a>
                    <a
                        href="{{ route('reports.absensi') }}"
                        title="Laporan"
                        class="nav-texmaco {{ request()->routeIs('reports.absensi') ? 'nav-texmaco-active' : '' }}"
                    >
                        <span class="nav-icon" aria-hidden="true">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18M7 15V9m4 6V5m4 10v-4" />
                            </svg>
                        </span>
                        <span class="nav-text">Laporan</span>
                    </a>
                @elseif($userRole === 'guru')
                    <a
                        href="{{ route('reports.absensi') }}"
                        title="Laporan Absensi"
                        class="nav-texmaco border-b border-slate-200 {{ request()->routeIs('reports.absensi') ? 'nav-texmaco-active' : '' }}"
                    >
                        <span class="nav-icon" aria-hidden="true">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18M7 15V9m4 6V5m4 10v-4" />
                            </svg>
                        </span>
                        <span class="nav-text">Laporan Absensi</span>
                    </a>
                @elseif($userRole === 'siswa')
                    <a
                        href="{{ route('absensi.student') }}"
                        title="Absen"
                        class="nav-texmaco border-b border-slate-200 {{ request()->routeIs('absensi.student') ? 'nav-texmaco-active' : '' }}"
                    >
                        <span class="nav-icon" aria-hidden="true">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m-7-3h7.5a2.25 2.25 0 012.25 2.25v9a2.25 2.25 0 01-2.25 2.25h-9A2.25 2.25 0 014 18.25v-9A2.25 2.25 0 016.25 7h.5m7-3H9a2.25 2.25 0 00-2.25 2.25v.75h10.5V6.25A2.25 2.25 0 0015 4z" />
                            </svg>
                        </span>
                        <span class="nav-text">Absen</span>
                    </a>
                @endif

                <a
                    href="{{ route('profile.index') }}"
                    title="Profil"
                    class="nav-texmaco {{ request()->routeIs('profile.index') ? 'nav-texmaco-active' : '' }}"
                >
                    <span class="nav-icon" aria-hidden="true">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3a9 9 0 100 18 9 9 0 000-18zm3.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM6.75 18a6.75 6.75 0 0110.5 0" />
                        </svg>
                    </span>
                    <span class="nav-text">Profil</span>
                </a>
            </nav>

            <div class="sidebar-brand-hide mt-auto border-t border-sky-100/80 bg-white/50 p-4 text-[11px] leading-relaxed text-slate-500 backdrop-blur-sm">
                <p class="font-medium text-slate-600">
                    {{ $userRole === 'tata_usaha' ? 'Panel Tata Usaha' : ($userRole === 'guru' ? 'Panel Guru' : 'Panel Siswa') }}
                </p>
                <p class="mt-1 text-slate-400">{{ $userRole === 'tata_usaha' ? 'Kelola absensi & laporan harian.' : ($userRole === 'guru' ? 'Monitoring dan laporan kehadiran siswa.' : 'Isi absen dan lihat profil Anda.') }}</p>
            </div>
        </aside>

        <!-- Konten utama -->
        <div
            id="main-shell"
            @class([
                'flex min-h-screen flex-1 flex-col transition-[margin] duration-200 ease-out lg:ml-64 overflow-y-auto overflow-x-hidden',
                'lg:h-full lg:min-h-0 lg:overflow-hidden' => request()->routeIs('dashboard'),
            ])
        >
            <header class="sticky top-0 z-30 flex shrink-0 items-center justify-between gap-4 border-b border-slate-200 bg-white/95 px-4 shadow-sm backdrop-blur-sm sm:px-6 @hasSection('page_subtitle') min-h-[4.25rem] py-2 @else h-16 @endif">
                <div class="flex min-w-0 flex-1 items-center gap-3">
                    <button
                        type="button"
                        id="sidebar-toggle-header"
                        class="inline-flex shrink-0 rounded-xl p-2 text-slate-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-sky-200 lg:hidden"
                        aria-controls="admin-sidebar"
                        aria-expanded="false"
                        aria-label="Buka menu"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="min-w-0 leading-tight">
                        <h1 class="truncate text-lg font-bold tracking-tight text-slate-900 sm:text-xl">
                            @yield('page_title', 'SITEXA Absensi')
                        </h1>
                        @hasSection('page_subtitle')
                            <p class="truncate text-xs font-medium text-slate-500 sm:text-sm">
                                @yield('page_subtitle')
                            </p>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-4">
                    @php
                        if (auth()->user()->role === 'guru') {
                            $pendingCount = \App\Models\LeaveRequest::query()
                                ->where('status', 'pending_teacher')
                                ->count();
                        } elseif (in_array(auth()->user()->role, ['admin', 'tu', 'tata_usaha'])) {
                            $pendingCount = \App\Models\LeaveRequest::query()
                                ->where('status', 'pending_admin')
                                ->count();
                        } else {
                            // Student - count their own pending requests
                            $student = \App\Models\Student::where('email', auth()->user()->email)->first();
                            $pendingCount = $student ? \App\Models\LeaveRequest::query()
                                ->where('student_id', $student->id)
                                ->whereIn('status', ['pending_teacher', 'pending_admin'])
                                ->count() : 0;
                        }
                    @endphp

                    @if($pendingCount > 0)
                        <a
                            href="{{ auth()->user()->role === 'guru' ? route('dashboard') : (auth()->user()->role === 'siswa' ? route('dashboard') : route('notifications.tu-approvals')) }}"
                            class="relative inline-flex rounded-xl p-2.5 text-slate-600 hover:bg-slate-100"
                            id="notification-btn"
                            aria-label="Ada {{ $pendingCount }} permintaan izin/sakit"
                            title="{{ $pendingCount }} izin/sakit menunggu persetujuan"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="absolute -right-1 -top-1 inline-flex min-h-[18px] min-w-[18px] items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white ring-2 ring-white">
                                {{ $pendingCount > 9 ? '9+' : $pendingCount }}
                            </span>
                        </a>
                    @else
                        <a
                            href="{{ auth()->user()->role === 'guru' ? route('dashboard') : (auth()->user()->role === 'siswa' ? route('dashboard') : route('notifications.tu-approvals')) }}"
                            class="relative inline-flex rounded-xl p-2.5 text-slate-600 hover:bg-slate-100"
                            id="notification-btn"
                            aria-label="Persetujuan izin dan sakit"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </a>
                    @endif

                    <div class="flex items-center gap-2 border-l border-slate-200 pl-3 sm:gap-3 sm:pl-4">
                        <div class="hidden text-right text-sm sm:block">
                            <p class="font-semibold leading-tight text-slate-900">{{ auth()->user()->name ?? 'Admin' }}</p>
                            <p class="text-xs text-slate-500">{{ auth()->user()->email ?? 'Tata Usaha' }}</p>
                        </div>
                        <div class="relative">
                            <button
                                type="button"
                                id="profile-menu-button"
                                class="flex h-10 w-10 shrink-0 overflow-hidden rounded-full border border-slate-200 bg-slate-100 text-sm font-bold text-slate-700 transition-colors hover:ring-2 hover:ring-sky-300"
                                aria-haspopup="true"
                                aria-expanded="false"
                                aria-label="Buka menu profil"
                            >
                                @if(auth()->user()->photo)
                                    <img src="{{ auth()->user()->photo_url }}" alt="{{ auth()->user()->name }}" class="h-full w-full object-cover" />
                                @else
                                    <span class="inline-flex h-full w-full items-center justify-center">{{ strtoupper(\Illuminate\Support\Str::substr(auth()->user()->name ?? 'A', 0, 1)) }}</span>
                                @endif
                            </button>
                            <div id="profile-menu" class="profile-menu hidden" role="menu" aria-hidden="true">
                                <a href="{{ route('profile.index') }}" class="profile-menu-item" role="menuitem">Profil</a>
                                <div class="profile-menu-divider" role="separator"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="profile-menu-item w-full text-left" role="menuitem">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main
                @class([
                    'flex-1 px-4 py-6 sm:px-6',
                    'lg:flex lg:min-h-0 lg:flex-1 lg:flex-col lg:overflow-hidden lg:py-3 lg:pb-4' => request()->routeIs('dashboard'),
                ])
            >
                <div id="content-area" class="flex-1 min-h-screen overflow-y-auto {{ request()->routeIs('dashboard') ? 'lg:flex lg:min-h-0 lg:flex-1 lg:flex-col lg:overflow-hidden' : '' }}">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        (function () {
            var root = document.getElementById("layout-root");
            var sidebar = document.getElementById("admin-sidebar");
            var backdrop = document.getElementById("sidebar-backdrop");
            var toggle = document.getElementById("sidebar-toggle");
            var toggleHeader = document.getElementById("sidebar-toggle-header");

            function isLarge() {
                return window.matchMedia("(min-width: 1024px)").matches;
            }

            function setMobileOpen(open) {
                if (!sidebar || !backdrop) return;
                if (open) {
                    sidebar.classList.remove("-translate-x-full");
                    sidebar.classList.add("translate-x-0");
                    backdrop.classList.remove("hidden");
                } else {
                    sidebar.classList.add("-translate-x-full");
                    sidebar.classList.remove("translate-x-0");
                    backdrop.classList.add("hidden");
                }
                if (toggle) toggle.setAttribute("aria-expanded", open ? "true" : "false");
                if (toggleHeader) toggleHeader.setAttribute("aria-expanded", open ? "true" : "false");
            }

            function closeMobileIfNeeded() {
                if (!isLarge()) setMobileOpen(false);
            }

            // Restore state for desktop sidebar
            if (isLarge() && localStorage.getItem('sidebar-collapsed') === 'true') {
                root.classList.add("sidebar-collapsed");
            }

            if (toggle) {
                toggle.addEventListener("click", function () {
                    if (isLarge()) {
                        root.classList.toggle("sidebar-collapsed");
                        localStorage.setItem('sidebar-collapsed', root.classList.contains("sidebar-collapsed"));
                        return;
                    }
                    var open = sidebar.classList.contains("-translate-x-full");
                    setMobileOpen(open);
                });
            }

            if (toggleHeader) {
                toggleHeader.addEventListener("click", function () {
                    var open = sidebar.classList.contains("-translate-x-full");
                    setMobileOpen(open);
                });
            }

            if (backdrop) {
                backdrop.addEventListener("click", function () {
                    setMobileOpen(false);
                });
            }

            var profileButton = document.getElementById("profile-menu-button");
            var profileMenu = document.getElementById("profile-menu");

            function closeProfileMenu() {
                if (!profileMenu || !profileButton) return;
                profileMenu.classList.add("hidden");
                profileButton.setAttribute("aria-expanded", "false");
                profileMenu.setAttribute("aria-hidden", "true");
            }

            function toggleProfileMenu() {
                if (!profileMenu || !profileButton) return;
                var isHidden = profileMenu.classList.contains("hidden");
                if (isHidden) {
                    profileMenu.classList.remove("hidden");
                    profileButton.setAttribute("aria-expanded", "true");
                    profileMenu.setAttribute("aria-hidden", "false");
                } else {
                    closeProfileMenu();
                }
            }

            if (profileButton) {
                profileButton.addEventListener("click", function (event) {
                    event.stopPropagation();
                    toggleProfileMenu();
                });
            }

            document.addEventListener("click", function (event) {
                if (!profileMenu || !profileButton) return;
                if (profileMenu.contains(event.target) || profileButton.contains(event.target)) return;
                closeProfileMenu();
            });

            document.addEventListener("keydown", function (event) {
                if (event.key === "Escape") {
                    closeProfileMenu();
                }
            });

            window.addEventListener("resize", function () {
                if (isLarge()) {
                    setMobileOpen(false);
                    backdrop.classList.add("hidden");
                }
            });
        })();
    </script>
    @stack('scripts')

    <!-- Notification Container -->
    <div id="notification-container" class="fixed top-4 right-4 z-[9999] flex flex-col gap-2 pointer-events-none"></div>

    <script>
        // Notification System
        (function() {
            const container = document.getElementById('notification-container');
            const shownNotifications = new Set();
            let pollTimer = null;
            let pollCount = 0;

            function showNotification(notification) {
                if (!container || shownNotifications.has(notification.id)) return;
                shownNotifications.add(notification.id);

                const notificationEl = document.createElement('div');
                notificationEl.className = 'pointer-events-auto max-w-sm rounded-2xl border border-slate-200 bg-white p-4 shadow-lg transition-all duration-300 transform translate-x-full opacity-0';
                notificationEl.innerHTML = `
                    <div class="flex items-start gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full ${notification.type === 'success' ? 'bg-emerald-100 text-emerald-600' : notification.type === 'warning' ? 'bg-amber-100 text-amber-600' : notification.type === 'error' ? 'bg-red-100 text-red-600' : 'bg-sky-100 text-sky-600'}">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                ${notification.type === 'success' ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />' : notification.type === 'warning' ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />' : notification.type === 'error' ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />' : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'}
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-900">${notification.title}</p>
                            <p class="mt-1 text-xs text-slate-600">${notification.message}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="shrink-0 rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                `;

                container.appendChild(notificationEl);

                // Animate in
                requestAnimationFrame(() => {
                    notificationEl.classList.remove('translate-x-full', 'opacity-0');
                });

                // Auto dismiss after 5 seconds
                setTimeout(() => {
                    notificationEl.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => notificationEl.remove(), 300);
                }, 5000);
            }

            async function pollNotifications() {
                try {
                    const response = await fetch('/api/notifications', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        if (data.notifications && Array.isArray(data.notifications)) {
                            data.notifications.forEach(showNotification);
                        }
                    }
                } catch (error) {
                    // Silent fail - don't show errors for notification polling
                } finally {
                    // Poll every 30 seconds
                    pollTimer = setTimeout(pollNotifications, 30000);
                }
            }

            // Start polling
            if (container) {
                pollNotifications();
            }
        })();
    </script>
</body>
</html>

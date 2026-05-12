<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'SITEXA Absensi — Texmaco Purwasari')</title>
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

            <nav class="flex flex-1 flex-col gap-1 overflow-y-auto p-3 pt-4" aria-label="Menu utama">
                <a
                    href="{{ route('dashboard') }}"
                    title="Dashboard"
                    class="nav-texmaco border-b border-slate-200 {{ request()->routeIs('dashboard') ? 'nav-texmaco-active' : '' }}"
                >
                    <span class="nav-icon" aria-hidden="true">DB</span>
                    <span class="nav-text">Dashboard</span>
                </a>
                <a
                    href="{{ route('students.index') }}"
                    title="Data Siswa"
                    class="nav-texmaco border-b border-slate-200 {{ request()->routeIs('students.index') ? 'nav-texmaco-active' : '' }}"
                >
                    <span class="nav-icon" aria-hidden="true">DS</span>
                    <span class="nav-text">Data Siswa</span>
                </a>
                <a
                    href="{{ route('teachers.index') }}"
                    title="Data Guru"
                    class="nav-texmaco border-b border-slate-200 {{ request()->routeIs('teachers.index') ? 'nav-texmaco-active' : '' }}"
                >
                    <span class="nav-icon" aria-hidden="true">DG</span>
                    <span class="nav-text">Data Guru</span>
                </a>
                <a
                    href="{{ route('schedules.index') }}"
                    title="Jadwal"
                    class="nav-texmaco border-b border-slate-200 {{ request()->routeIs('schedules.index') ? 'nav-texmaco-active' : '' }}"
                >
                    <span class="nav-icon" aria-hidden="true">JD</span>
                    <span class="nav-text">Jadwal</span>
                </a>
                <a
                    href="{{ route('requests.izin-sakit') }}"
                    title="Izin & Sakit"
                    class="nav-texmaco border-b border-slate-200 {{ request()->routeIs('requests.izin-sakit') ? 'nav-texmaco-active' : '' }}"
                >
                    <span class="nav-icon" aria-hidden="true">IS</span>
                    <span class="nav-text">Izin &amp; Sakit</span>
                </a>
                <a
                    href="{{ route('monitoring.nfc') }}"
                    title="Monitoring NFC"
                    class="nav-texmaco border-b border-slate-200 {{ request()->routeIs('monitoring.nfc') ? 'nav-texmaco-active' : '' }}"
                >
                    <span class="nav-icon" aria-hidden="true">MN</span>
                    <span class="nav-text">Monitoring NFC</span>
                </a>
                <a
                    href="{{ route('devices.nfc-tools') }}"
                    title="Alat NFC"
                    class="nav-texmaco border-b border-slate-200 {{ request()->routeIs('devices.nfc-tools') ? 'nav-texmaco-active' : '' }}"
                >
                    <span class="nav-icon" aria-hidden="true">AN</span>
                    <span class="nav-text">Alat NFC</span>
                </a>
                <a
                    href="{{ route('reports.absensi') }}"
                    title="Laporan"
                    class="nav-texmaco {{ request()->routeIs('reports.absensi') ? 'nav-texmaco-active' : '' }}"
                >
                    <span class="nav-icon" aria-hidden="true">LP</span>
                    <span class="nav-text">Laporan</span>
                </a>
                <a
                    href="{{ route('settings.index') }}"
                    title="Pengaturan"
                    class="nav-texmaco {{ request()->routeIs('settings.index') ? 'nav-texmaco-active' : '' }}"
                >
                    <span class="nav-icon" aria-hidden="true">PG</span>
                    <span class="nav-text">Pengaturan</span>
                </a>
                <a
                    href="{{ route('profile.index') }}"
                    title="Profil"
                    class="nav-texmaco {{ request()->routeIs('profile.index') ? 'nav-texmaco-active' : '' }}"
                >
                    <span class="nav-icon" aria-hidden="true">PR</span>
                    <span class="nav-text">Profil</span>
                </a>
            </nav>

            <div class="sidebar-brand-hide mt-auto border-t border-sky-100/80 bg-white/50 p-4 text-[11px] leading-relaxed text-slate-500 backdrop-blur-sm">
                <p class="font-medium text-slate-600">Panel Tata Usaha</p>
                <p class="mt-1 text-slate-400">Kelola absensi &amp; laporan harian.</p>
            </div>
        </aside>

        <!-- Konten utama -->
        <div
            id="main-shell"
            @class([
                'flex min-h-screen flex-1 flex-col transition-[margin] duration-200 ease-out lg:ml-64',
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
                    <div class="hidden sm:block">
                        <label class="sr-only" for="global-search">Cari</label>
                        <input
                            id="global-search"
                            type="search"
                            placeholder="Cari..."
                            class="w-40 rounded-2xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-900 placeholder-slate-400 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100 md:w-48"
                        />
                    </div>

                    <a
                        href="{{ route('notifications.guru-approvals') }}"
                        class="relative inline-flex rounded-xl p-2.5 text-slate-600 hover:bg-slate-100"
                        id="notification-btn"
                        aria-label="Persetujuan izin dan alpha dari guru"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute right-1.5 top-1.5 h-2 w-2 rounded-full bg-red-500 ring-2 ring-white" title="Menunggu tindakan"></span>
                    </a>

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
                                    <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="{{ auth()->user()->name }}" class="h-full w-full object-cover" />
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
                <div id="content-area" class="{{ request()->routeIs('dashboard') ? 'lg:flex lg:min-h-0 lg:flex-1 lg:flex-col lg:overflow-hidden' : '' }}">
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

            if (toggle) {
                toggle.addEventListener("click", function () {
                    if (isLarge()) {
                        root.classList.toggle("sidebar-collapsed");
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
</body>
</html>

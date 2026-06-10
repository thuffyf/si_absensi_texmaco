<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
    <title>@yield('title', 'Portal Siswa/Guru - SITEXA')</title>
    @include('partials.favicon')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen text-slate-900 font-sans antialiased">
    @php
        $userRole = auth()->user()->role ?? null;
        $portalTitle = $userRole === 'guru' ? 'Portal Guru' : 'Portal Siswa';
        $isStudentPortal = $userRole === 'siswa';

        $studentNavItems = [
            ['route' => 'portal.student.dashboard', 'match' => 'portal.student.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 3h7v7H3V3zm0 11h7v7H3v-7zm11-11h7v7h-7V3zm0 11h7v7h-7v-7z'],
            ['route' => 'portal.student.schedule', 'match' => 'portal.student.schedule', 'label' => 'Jadwal', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ['route' => 'portal.student.leave', 'match' => 'portal.student.leave*', 'label' => 'Izin & Sakit', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['route' => 'portal.student.history', 'match' => 'portal.student.history', 'label' => 'Riwayat', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
            ['route' => 'portal.student.profile', 'match' => 'portal.student.profile', 'label' => 'Profil', 'icon' => 'M12 3a9 9 0 100 18 9 9 0 000-18zm3.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM6.75 18a6.75 6.75 0 0110.5 0'],
        ];
    @endphp

    <div class="pointer-events-none fixed inset-0 z-0 bg-gradient-to-br from-blue-50 via-slate-50 to-slate-100" aria-hidden="true"></div>

    <div class="relative min-h-screen w-full">
        <div id="portal-layout-root" class="relative z-10 flex min-h-screen">
            <div
                id="portal-sidebar-backdrop"
                class="fixed inset-0 z-40 hidden bg-slate-900/35 backdrop-blur-[2px]"
                aria-hidden="true"
            ></div>

            <aside
                id="portal-sidebar"
                class="sidebar-shell fixed left-0 top-0 z-50 flex h-screen w-64 flex-col border-r border-sky-100/80 bg-gradient-to-b from-sky-50/90 via-white to-slate-50/95 shadow-[4px_0_24px_-8px_rgba(15,23,42,0.08)] backdrop-blur-sm transition-[width,margin,transform] duration-200 ease-out -translate-x-full lg:translate-x-0"
            >
                <div class="relative flex shrink-0 items-center border-b border-sky-100/90 bg-white/60 px-3 py-3 backdrop-blur-sm">
                    <button
                        type="button"
                        id="portal-sidebar-toggle"
                        class="inline-flex rounded-xl p-2.5 text-slate-700 ring-1 ring-slate-200/80 hover:bg-white hover:ring-sky-200 focus:outline-none focus:ring-2 focus:ring-sky-300"
                        aria-controls="portal-sidebar"
                        aria-expanded="false"
                        aria-label="Buka atau tutup menu"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div class="sidebar-brand-hide ml-3 min-w-0">
                        <p class="truncate text-sm font-bold text-slate-900">{{ $portalTitle }}</p>
                        <p class="truncate text-[11px] font-medium text-slate-500">SITEXA Texmaco</p>
                    </div>
                </div>

                <nav class="flex flex-1 flex-col gap-1 overflow-y-auto p-3 pt-4" aria-label="Menu portal">
                    @if ($userRole === 'siswa')
                        @foreach ($studentNavItems as $index => $item)
                            <a
                                href="{{ route($item['route']) }}"
                                title="{{ $item['label'] }}"
                                data-portal-nav-link
                                @class([
                                    'nav-texmaco',
                                    'border-b border-slate-200' => $index < count($studentNavItems) - 1,
                                    'nav-texmaco-active' => request()->routeIs($item['match']),
                                ])
                            >
                                <span class="nav-icon" aria-hidden="true">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                                    </svg>
                                </span>
                                <span class="nav-text">{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    @elseif ($userRole === 'guru')
                        <a
                            href="{{ route('portal.teacher.attendance') }}"
                            title="Kehadiran Siswa"
                            data-portal-nav-link
                            class="nav-texmaco {{ request()->routeIs('portal.teacher.attendance*') ? 'nav-texmaco-active' : '' }}"
                        >
                            <span class="nav-icon" aria-hidden="true">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m-7-3h7.5a2.25 2.25 0 012.25 2.25v9a2.25 2.25 0 01-2.25 2.25h-9A2.25 2.25 0 014 18.25v-9A2.25 2.25 0 016.25 7h.5m7-3H9a2.25 2.25 0 00-2.25 2.25v.75h10.5V6.25A2.25 2.25 0 0015 4z" />
                                </svg>
                            </span>
                            <span class="nav-text">Kehadiran Siswa</span>
                        </a>
                    @endif
                </nav>

                <div class="sidebar-brand-hide mt-auto border-t border-sky-100/80 bg-white/50 p-4 text-[11px] leading-relaxed text-slate-500 backdrop-blur-sm">
                    <p class="font-medium text-slate-600">{{ $portalTitle }}</p>
                    <p class="mt-1 text-slate-400">
                        {{ $userRole === 'guru' ? 'Monitoring kehadiran siswa.' : 'Lihat jadwal, izin, dan riwayat absensi.' }}
                    </p>
                    <form method="POST" action="{{ route('portal.logout') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                            Keluar
                        </button>
                    </form>
                </div>
            </aside>

            <div id="portal-main-shell" class="flex min-h-screen flex-1 flex-col transition-[margin] duration-200 ease-out lg:ml-64">
                <header class="sticky top-0 z-30 flex shrink-0 items-center justify-between gap-3 border-b border-slate-200 bg-white/95 px-4 shadow-sm backdrop-blur-sm pt-[max(0.75rem,env(safe-area-inset-top))] pb-3 min-h-[4.25rem]">
                    <div class="flex min-w-0 flex-1 items-center gap-3">
                        <button
                            type="button"
                            id="portal-sidebar-toggle-header"
                            class="inline-flex shrink-0 rounded-xl p-2 text-slate-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-sky-200 lg:hidden"
                            aria-controls="portal-sidebar"
                            aria-expanded="false"
                            aria-label="Buka menu"
                        >
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div class="min-w-0 leading-tight">
                            <h1 class="truncate text-base font-bold tracking-tight text-slate-900 sm:text-lg">
                                @yield('page_title', 'Portal')
                            </h1>
                            @hasSection('page_subtitle')
                                <p class="truncate text-xs font-medium text-slate-500">
                                    @yield('page_subtitle')
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="hidden text-right text-sm sm:block">
                            <p class="font-semibold leading-tight text-slate-900">{{ auth()->user()->name ?? 'Pengguna' }}</p>
                            <p class="text-xs text-slate-500">{{ auth()->user()->email ?? $portalTitle }}</p>
                        </div>
                        <div class="relative">
                            <button
                                type="button"
                                id="portal-profile-menu-button"
                                class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-full border border-slate-200 bg-slate-100 text-sm font-bold text-slate-700 transition-colors hover:ring-2 hover:ring-sky-300"
                                aria-haspopup="true"
                                aria-expanded="false"
                                aria-label="Buka menu profil"
                            >
                                {{ strtoupper(substr(trim(auth()->user()->name ?: 'P'), 0, 1)) }}
                            </button>
                            <div id="portal-profile-menu" class="profile-menu hidden" role="menu" aria-hidden="true" style="right:0;left:auto;min-width:10rem;">
                                @if(auth()->user()->role === 'siswa')
                                    <a href="{{ route('portal.student.profile') }}" class="profile-menu-item" role="menuitem">Profil</a>
                                @elseif(auth()->user()->role === 'guru')
                                    <a href="{{ route('portal.teacher.profile') }}" class="profile-menu-item" role="menuitem">Profil</a>
                                @endif
                                <div class="profile-menu-divider" role="separator"></div>
                                <form method="POST" action="{{ route('portal.logout') }}">
                                    @csrf
                                    <button type="submit" class="profile-menu-item w-full text-left" role="menuitem">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="flex-1 px-4 py-5 sm:px-6 lg:px-8" style="padding-bottom: max(2rem, env(safe-area-inset-bottom))">
                    <div class="mx-auto w-full max-w-7xl">
                    @if (session('success'))
                        <div class="portal-toast mb-4 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm" role="status">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="portal-toast mb-4 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-sm" role="alert">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    @yield('content')
                    </div>
                </main>
            </div>
        </div>
    </div>

    <script>
        (function () {
            var root = document.getElementById('portal-layout-root');
            var sidebar = document.getElementById('portal-sidebar');
            var backdrop = document.getElementById('portal-sidebar-backdrop');
            var toggle = document.getElementById('portal-sidebar-toggle');
            var toggleHeader = document.getElementById('portal-sidebar-toggle-header');

            function isLarge() {
                return window.matchMedia('(min-width: 1024px)').matches;
            }

            function setMobileOpen(open) {
                if (!sidebar || !backdrop) return;
                if (open) {
                    sidebar.classList.remove('-translate-x-full');
                    sidebar.classList.add('translate-x-0');
                    backdrop.classList.remove('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    sidebar.classList.remove('translate-x-0');
                    backdrop.classList.add('hidden');
                }
                if (toggle) toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                if (toggleHeader) toggleHeader.setAttribute('aria-expanded', open ? 'true' : 'false');
            }

            if (isLarge() && localStorage.getItem('portal-sidebar-collapsed') === 'true') {
                root.classList.add('portal-sidebar-collapsed');
            }

            if (toggle) {
                toggle.addEventListener('click', function () {
                    if (isLarge()) {
                        root.classList.toggle('portal-sidebar-collapsed');
                        localStorage.setItem('portal-sidebar-collapsed', root.classList.contains('portal-sidebar-collapsed'));
                        return;
                    }
                    var open = sidebar.classList.contains('-translate-x-full');
                    setMobileOpen(open);
                });
            }

            if (toggleHeader) {
                toggleHeader.addEventListener('click', function () {
                    var open = sidebar.classList.contains('-translate-x-full');
                    setMobileOpen(open);
                });
            }

            if (backdrop) {
                backdrop.addEventListener('click', function () {
                    setMobileOpen(false);
                });
            }

            document.querySelectorAll('[data-portal-nav-link]').forEach(function (link) {
                link.addEventListener('click', function () {
                    setMobileOpen(false);
                });
            });

            document.querySelectorAll('.portal-toast').forEach(function (toast) {
                window.setTimeout(function () {
                    toast.classList.add('opacity-0', 'translate-y-1');
                    window.setTimeout(function () {
                        toast.remove();
                    }, 300);
                }, 4500);
            });

            var portalProfileButton = document.getElementById('portal-profile-menu-button');
            var portalProfileMenu = document.getElementById('portal-profile-menu');

            function closePortalProfileMenu() {
                if (!portalProfileMenu || !portalProfileButton) return;
                portalProfileMenu.classList.add('hidden');
                portalProfileButton.setAttribute('aria-expanded', 'false');
                portalProfileMenu.setAttribute('aria-hidden', 'true');
            }

            if (portalProfileButton) {
                portalProfileButton.addEventListener('click', function (event) {
                    event.stopPropagation();
                    var isHidden = portalProfileMenu.classList.contains('hidden');
                    if (isHidden) {
                        portalProfileMenu.classList.remove('hidden');
                        portalProfileButton.setAttribute('aria-expanded', 'true');
                        portalProfileMenu.setAttribute('aria-hidden', 'false');
                    } else {
                        closePortalProfileMenu();
                    }
                });
            }

            document.addEventListener('click', function (event) {
                if (!portalProfileMenu || !portalProfileButton) return;
                if (portalProfileMenu.contains(event.target) || portalProfileButton.contains(event.target)) return;
                closePortalProfileMenu();
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') closePortalProfileMenu();
            });

            window.addEventListener('resize', function () {
                if (isLarge()) setMobileOpen(false);
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>

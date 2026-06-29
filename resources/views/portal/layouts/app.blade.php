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
            ['route' => 'portal.student.dashboard', 'match' => 'portal.student.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['route' => 'portal.student.absensi', 'match' => 'portal.student.absensi*', 'label' => 'Absensi', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
            ['route' => 'portal.student.schedule', 'match' => 'portal.student.schedule', 'label' => 'Jadwal', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ['route' => 'portal.student.profile', 'match' => 'portal.student.profile', 'label' => 'Profil', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
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
                        {{ $userRole === 'guru' ? 'Monitoring kehadiran siswa.' : 'Dashboard, absensi, jadwal, dan profil.' }}
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

                    <div class="flex items-center gap-2 sm:gap-3">
                        @if(auth()->user()->role === 'siswa')
                            @php
                                $recentResponses = collect();
                                $recentResponsesCount = 0;
                                $student = \App\Models\Student::where('email', auth()->user()->email)->first();
                                if ($student) {
                                    $recentResponses = \App\Models\LeaveRequest::where('student_id', $student->id)
                                        ->whereNotNull('responded_at')
                                        ->orderBy('responded_at', 'desc')
                                        ->take(5)
                                        ->get();
                                    
                                    $recentResponsesCount = $recentResponses->where('responded_at', '>=', now()->subDays(3))->count();
                                }
                            @endphp

                            {{-- Notification Bell --}}
                            <div class="relative">
                                <button type="button" id="portal-notification-btn" class="relative inline-flex rounded-xl p-2.5 text-slate-600 hover:bg-slate-100 transition-colors focus:outline-none" aria-haspopup="true" aria-expanded="false">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    @if($recentResponsesCount > 0)
                                        <span class="absolute right-2 top-2 flex h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                                    @endif
                                </button>

                                {{-- Notification Dropdown --}}
                                <div id="portal-notification-menu" class="profile-menu hidden w-[280px] sm:w-80" role="menu" aria-hidden="true" style="right:0;left:auto;top:110%;transform-origin:top right;">
                                    <div class="px-4 py-3 border-b border-slate-100 flex justify-between items-center bg-slate-50/50 rounded-t-2xl">
                                        <h3 class="text-sm font-bold text-slate-800">Notifikasi Izin & Sakit</h3>
                                    </div>
                                    <div class="max-h-80 overflow-y-auto p-2">
                                        @forelse($recentResponses as $req)
                                            <div class="px-3 py-3 rounded-xl hover:bg-slate-50 transition-colors border-b border-slate-50 last:border-0">
                                                <div class="flex items-start gap-3">
                                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full {{ $req->status === 'approved' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                                                        @if($req->status === 'approved')
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                        @else
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-semibold text-slate-800">Izin/Sakit {{ $req->status === 'approved' ? 'Disetujui' : 'Ditolak' }}</p>
                                                        <p class="text-xs text-slate-500 mt-0.5 line-clamp-2">Pengajuan {{ ucfirst($req->type) }} Anda untuk {{ \Carbon\Carbon::parse($req->start_date ?? $req->request_date)->format('d M') }} telah {{ $req->status === 'approved' ? 'disetujui' : 'ditolak (' . ($req->rejection_reason ?? 'Tanpa alasan') . ')' }}.</p>
                                                        <p class="text-[10px] text-slate-400 mt-1 font-medium">{{ \Carbon\Carbon::parse($req->responded_at)->diffForHumans() }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="px-4 py-8 text-center">
                                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-50 mb-2">
                                                    <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                                                </div>
                                                <p class="text-sm font-medium text-slate-500">Belum ada notifikasi</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            
                            <div class="h-6 w-px bg-slate-200 hidden sm:block mx-1"></div>
                        @endif

                        <div class="hidden text-right text-sm sm:block pr-2">
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
                                @if(auth()->user()?->photo)
                                    <img src="{{ auth()->user()->photo_url }}" alt="{{ auth()->user()->name ?? 'Pengguna' }}" class="h-full w-full object-cover" />
                                @else
                                    <img src="{{ auth()->user()->photo_url }}" alt="{{ auth()->user()->name ?? 'Pengguna' }}" class="h-full w-full object-cover" />
                                @endif
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
            var portalNotificationButton = document.getElementById('portal-notification-btn');
            var portalNotificationMenu = document.getElementById('portal-notification-menu');

            function closePortalProfileMenu() {
                if (!portalProfileMenu || !portalProfileButton) return;
                portalProfileMenu.classList.add('hidden');
                portalProfileButton.setAttribute('aria-expanded', 'false');
                portalProfileMenu.setAttribute('aria-hidden', 'true');
            }

            function closePortalNotificationMenu() {
                if (!portalNotificationMenu || !portalNotificationButton) return;
                portalNotificationMenu.classList.add('hidden');
                portalNotificationButton.setAttribute('aria-expanded', 'false');
                portalNotificationMenu.setAttribute('aria-hidden', 'true');
            }

            if (portalProfileButton) {
                portalProfileButton.addEventListener('click', function (event) {
                    event.stopPropagation();
                    closePortalNotificationMenu();
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

            if (portalNotificationButton) {
                portalNotificationButton.addEventListener('click', function (event) {
                    event.stopPropagation();
                    closePortalProfileMenu();
                    var isHidden = portalNotificationMenu.classList.contains('hidden');
                    if (isHidden) {
                        portalNotificationMenu.classList.remove('hidden');
                        portalNotificationButton.setAttribute('aria-expanded', 'true');
                        portalNotificationMenu.setAttribute('aria-hidden', 'false');
                    } else {
                        closePortalNotificationMenu();
                    }
                });
            }

            document.addEventListener('click', function (event) {
                if (portalProfileMenu && portalProfileButton && (!portalProfileMenu.contains(event.target) && !portalProfileButton.contains(event.target))) {
                    closePortalProfileMenu();
                }
                if (portalNotificationMenu && portalNotificationButton && (!portalNotificationMenu.contains(event.target) && !portalNotificationButton.contains(event.target))) {
                    closePortalNotificationMenu();
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closePortalProfileMenu();
                    closePortalNotificationMenu();
                }
            });

            window.addEventListener('resize', function () {
                if (isLarge()) setMobileOpen(false);
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Sistem Absensi NFC Texmaco</title>
    @include('partials.favicon')
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-slate-100 text-slate-900 flex items-center justify-center p-4">
    <div class="w-full max-w-5xl">
        <div class="grid grid-cols-1 md:grid-cols-[0.95fr_1.05fr] gap-5">
            <!-- Left panel -->
            <div class="flex flex-col justify-center items-center px-6 py-10">
                <div class="space-y-6 text-center">
                    <img src="{{ asset('images/Logo Texmaco.jpg') }}" alt="SMK Purwasari Texmaco" class="h-64 object-contain mx-auto opacity-90" />
                </div>
            </div>

            <!-- Right panel -->
            <div class="flex items-center justify-center">
                <div class="w-full max-w-md rounded-3xl bg-white border border-slate-200 p-8 shadow-lg">
                    <div class="mb-10 text-center">
                        <img src="{{ asset('images/Texmaco Teks.jpeg') }}" alt="TEXMACO" class="mx-auto h-20 object-contain" />
                    </div>

                    @php
                        $requestedMode = request('mode') === 'portal' ? 'portal' : 'admin';
                        $activeMode = old('login_mode', $errors->has('portal_login') ? 'portal' : $requestedMode);
                    @endphp

                    <div class="mb-6 grid grid-cols-2 rounded-2xl bg-slate-100 p-1">
                        <button
                            type="button"
                            data-mode-target="admin"
                            class="login-mode-btn rounded-2xl px-4 py-2.5 text-sm font-semibold transition {{ $activeMode === 'admin' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500' }}"
                        >
                            Admin / TU
                        </button>
                        <button
                            type="button"
                            data-mode-target="portal"
                            class="login-mode-btn rounded-2xl px-4 py-2.5 text-sm font-semibold transition {{ $activeMode === 'portal' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500' }}"
                        >
                            Siswa / Guru
                        </button>
                    </div>

                    <div id="admin-login-panel" class="{{ $activeMode === 'admin' ? '' : 'hidden' }}">
                        <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
                            @csrf
                            <input type="hidden" name="login_mode" value="admin" />

                            @if ($errors->has('login'))
                                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                    {{ $errors->first('login') }}
                                </div>
                            @endif

                            <div>
                                <input id="username" name="username" type="email" required placeholder="Email admin / tata usaha"
                                    class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 bg-slate-50 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100 placeholder-slate-400" value="{{ old('username') }}" />
                            </div>

                            <div>
                                <div class="relative">
                                    <input id="password" name="password" type="password" required placeholder="Password"
                                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 pr-12 text-slate-900 bg-slate-50 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100 placeholder-slate-400" />
                                    <button type="button" id="togglePassword" class="absolute right-4 top-1/2 hidden -translate-y-1/2 transform text-slate-500 hover:text-slate-700 focus:outline-none">
                                        <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            @if ($errors->has('captcha'))
                                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                    {{ $errors->first('captcha') }}
                                </div>
                            @endif

                            @unless($recaptchaBypass ?? false)
                                <div class="flex justify-center my-4">
                                    @if($recaptchaSiteKey)
                                        <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}"></div>
                                    @else
                                        <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                                            Site key captcha belum diatur.
                                        </div>
                                    @endif
                                </div>
                            @endunless

                            <button type="submit" class="w-full rounded-2xl bg-slate-900 px-4 py-3 text-white font-bold text-lg hover:bg-slate-800 transition-colors">
                                MASUK ADMIN / TU
                            </button>
                        </form>
                    </div>

                    <div id="portal-login-panel" class="{{ $activeMode === 'portal' ? '' : 'hidden' }}">
                        <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
                            @csrf
                            <input type="hidden" name="login_mode" value="portal" />

                            @if ($errors->has('portal_login'))
                                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                    {{ $errors->first('portal_login') }}
                                </div>
                            @endif

                            <div>
                                <input id="portal_email" name="portal_email" type="email" required placeholder="Email siswa / guru"
                                    class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 bg-slate-50 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100 placeholder-slate-400" value="{{ old('portal_email') }}" />
                            </div>

                            <div>
                                <input id="birth_date" name="birth_date" type="date" required
                                    class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 bg-slate-50 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100" value="{{ old('birth_date') }}" />
                            </div>

                            <div class="rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-500">
                                Gunakan email dan tanggal lahir yang terdaftar di data sekolah.
                            </div>

                            <button type="submit" class="w-full rounded-2xl bg-sky-600 px-4 py-3 text-white font-bold text-lg hover:bg-sky-700 transition-colors">
                                MASUK SISWA / GURU
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @unless($recaptchaBypass ?? false)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endunless

    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');
        const modeButtons = document.querySelectorAll('.login-mode-btn');
        const adminPanel = document.getElementById('admin-login-panel');
        const portalPanel = document.getElementById('portal-login-panel');
        const eyeOpenIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
        const eyeClosedIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';

        function setMode(mode) {
            const isAdmin = mode === 'admin';
            adminPanel.classList.toggle('hidden', !isAdmin);
            portalPanel.classList.toggle('hidden', isAdmin);

            modeButtons.forEach((button) => {
                const active = button.dataset.modeTarget === mode;
                button.classList.toggle('bg-white', active);
                button.classList.toggle('text-slate-900', active);
                button.classList.toggle('shadow-sm', active);
                button.classList.toggle('text-slate-500', !active);
            });
        }

        function updatePasswordToggle() {
            const hasPassword = passwordInput.value.length > 0;
            togglePassword.classList.toggle('hidden', !hasPassword);

            if (!hasPassword) {
                passwordInput.type = 'password';
            }

            eyeIcon.innerHTML = passwordInput.type === 'password' ? eyeOpenIcon : eyeClosedIcon;
            togglePassword.setAttribute(
                'aria-label',
                passwordInput.type === 'password' ? 'Tampilkan password' : 'Sembunyikan password'
            );
        }

        passwordInput.addEventListener('input', updatePasswordToggle);
        modeButtons.forEach((button) => {
            button.addEventListener('click', function () {
                setMode(button.dataset.modeTarget);
            });
        });

        togglePassword.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }

            updatePasswordToggle();
        });

        updatePasswordToggle();
        setMode(@json($activeMode));
    </script>
</body>
</html>

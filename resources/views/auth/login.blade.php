<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
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
                                <img src="{{ asset('images/Logo Texmaco-transparent.png') }}" alt="SMK Purwasari Texmaco" class="h-64 object-contain mx-auto bg-transparent" style="background-color: transparent;" />
                </div>
            </div>

            <!-- Right panel -->
            <div class="flex items-center justify-center">
                <div class="w-full max-w-md rounded-3xl bg-white border border-slate-200 p-8 shadow-lg">
                    <div class="mb-10 text-center">
                        <img src="{{ asset('images/Texmaco Teks.jpeg') }}" alt="TEXMACO" class="mx-auto h-20 object-contain" />
                    </div>

                    <form action="{{ route('login.submit') }}" method="POST" class="space-y-5" id="loginForm">
                        @csrf

                        @if ($errors->has('login'))
                            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                {{ $errors->first('login') }}
                            </div>
                        @endif

                        @php
                            $captchaError = $errors->first('captcha') ?: $errors->first('g-recaptcha-response');
                        @endphp

                        @if ($captchaError)
                            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                {{ $captchaError }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div>
                            <input id="username" name="username" type="text" required placeholder="Email / NIP"
                                class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 bg-slate-50 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100 placeholder-slate-400" value="{{ old('username') }}" autocomplete="username" />
                        </div>

                        <div>
                            <div class="relative">
                                <input id="password" name="password" type="password" required placeholder="Password"
                                    class="w-full rounded-2xl border border-slate-300 px-4 py-3 pr-12 text-slate-900 bg-slate-50 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100 placeholder-slate-400" autocomplete="current-password" />
                                <button type="button" id="togglePassword" class="absolute right-4 top-1/2 hidden -translate-y-1/2 transform text-slate-500 hover:text-slate-700 focus:outline-none" aria-label="Tampilkan password">
                                    <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        @if ($captchaError)
                            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                {{ $captchaError }}
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

                        <div class="flex items-center justify-between gap-4">
                            <label class="inline-flex items-center text-sm">
                                <input type="checkbox" name="remember" id="remember" class="form-checkbox h-4 w-4 text-sky-600 rounded" {{ old('remember') ? 'checked' : '' }} />
                                <span class="ml-2">Ingat saya</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a id="forgotPasswordLink" href="{{ route('password.request') }}" class="text-sm text-sky-600 hover:underline">Lupa password?</a>
                            @endif
                        </div>

                        <button type="submit" class="w-full rounded-2xl bg-slate-900 px-4 py-3 text-white font-bold text-lg hover:bg-slate-800 transition-colors">
                            MASUK
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @unless($recaptchaBypass ?? false)
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endunless

    <script>
        // Saat halaman login dibuka dari browser back-forward cache setelah logout,
        // reload supaya hidden CSRF token mengikuti session terbaru.
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });

        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOpenIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
        const eyeClosedIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';

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

        togglePassword.addEventListener('click', function(e) {
            e.preventDefault();
            passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
            updatePasswordToggle();
        });

        updatePasswordToggle();

        // Auto-refresh CSRF token untuk mencegah error 419
        // Refresh token setiap 30 menit (setengah dari session lifetime 2 jam)
        setInterval(function() {
            fetch('{{ route("login") }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newToken = doc.querySelector('input[name="_token"]');
                
                if (newToken) {
                    const currentToken = document.querySelector('input[name="_token"]');
                    if (currentToken) {
                        currentToken.value = newToken.value;
                        console.log('CSRF token refreshed');
                    }
                }
            })
            .catch(err => {
                console.error('Failed to refresh CSRF token:', err);
            });
        }, 30 * 60 * 1000); // 30 menit

        // Handle form submit untuk menangkap error 419
        const loginForm = document.getElementById('loginForm');
        loginForm.addEventListener('submit', function(e) {
            const submitBtn = loginForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                
                // Re-enable setelah 10 detik (fallback jika submit gagal)
                setTimeout(function() {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'MASUK';
                }, 10000);
            }
        });

        // Lupa password: jika field username berisi email, kirim permintaan reset otomatis
        const forgotLink = document.getElementById('forgotPasswordLink');
        if (forgotLink) {
            forgotLink.addEventListener('click', function(e) {
                const usernameVal = document.getElementById('username')?.value || '';
                const looksLikeEmail = usernameVal.includes('@');

                if (looksLikeEmail) {
                    e.preventDefault();
                    const tokenInput = document.querySelector('input[name="_token"]');
                    const token = tokenInput ? tokenInput.value : '';

                    fetch('{{ route('password.email') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ email: usernameVal })
                    })
                    .then(res => res.json().catch(() => ({})))
                    .then(data => {
                        // Jika response redirect/html, beri tahu user untuk cek email
                        alert('Jika email terdaftar, link reset telah dikirim. Silakan cek inbox (termasuk folder spam).');
                    })
                    .catch(err => {
                        console.error('Reset request failed', err);
                        // fallback: buka halaman form manual
                        window.location.href = forgotLink.href;
                    });
                }
                // jika bukan email, biarkan link menuju form input email
            });
        }
    </script>
</body>
</html>

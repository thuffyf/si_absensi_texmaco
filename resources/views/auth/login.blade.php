<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login - Sistem Absensi NFC Texmaco</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-slate-100 text-slate-900 flex items-center justify-center p-4">
    <div class="w-full max-w-5xl">
        <div class="grid grid-cols-1 md:grid-cols-[0.95fr_1.05fr] gap-5">
            <!-- Left panel -->
            <div class="flex flex-col justify-center items-center px-6 py-10">
                <div class="space-y-6 text-center">
                    <img src="{{ asset('images/Logo Texmaco-transparent.png') }}" alt="SMK Purwasari Texmaco" class="h-64 object-contain mx-auto opacity-50" />
                </div>
            </div>

            <!-- Right panel -->
            <div class="flex items-center justify-center">
                <div class="w-full max-w-md rounded-3xl bg-white border border-slate-200 p-8 shadow-lg">
                    <div class="mb-10 text-center">
                        <img src="{{ asset('images/Texmaco Teks.jpeg') }}" alt="TEXMACO" class="mx-auto h-20 object-contain" />
                    </div>

                    <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
                        @csrf

                        @if ($errors->has('login'))
                            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                {{ $errors->first('login') }}
                            </div>
                        @endif

                        <div>
                            <input id="username" name="username" type="email" required placeholder="Username"
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

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Berapa hasil dari {{ $num1 }} + {{ $num2 }}?</label>
                            <input name="captcha" type="number" required placeholder="Jawaban"
                                class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 bg-slate-50 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100 placeholder-slate-400" />
                        </div>

                        <button type="submit" class="w-full rounded-2xl bg-slate-900 px-4 py-3 text-white font-bold text-lg hover:bg-slate-800 transition-colors">
                            MASUK
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
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
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }

            updatePasswordToggle();
        });

        updatePasswordToggle();
    </script>
</body>
</html>

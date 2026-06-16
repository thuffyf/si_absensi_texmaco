<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password - Sistem Absensi NFC Texmaco</title>
    @include('partials.favicon')
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-slate-100 text-slate-900 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="rounded-3xl bg-white border border-slate-200 p-8 shadow-lg">
            <!-- Logo -->
            <div class="mb-8 text-center">
                <img src="{{ asset('images/Texmaco Teks.jpeg') }}" alt="TEXMACO" class="mx-auto h-16 object-contain mb-4" />
                <h1 class="text-2xl font-bold text-slate-900">Reset Password</h1>
                <p class="text-sm text-slate-600 mt-2">Masukkan password baru Anda</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 mb-6 space-y-2">
                    @foreach ($errors->all() as $error)
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <div>{{ $error }}</div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('password.update') }}" method="POST" class="space-y-5" id="resetForm">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email (readonly) -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        required 
                        readonly
                        value="{{ $email ?? old('email') }}"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 bg-slate-100 cursor-not-allowed" 
                    />
                </div>

                <!-- Password Baru -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password Baru</label>
                    <div class="relative">
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            required 
                            placeholder="Minimal 8 karakter"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 pr-12 text-slate-900 bg-slate-50 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100 placeholder-slate-400" 
                            autocomplete="new-password"
                        />
                        <button type="button" id="togglePassword" class="absolute right-4 top-1/2 -translate-y-1/2 transform text-slate-500 hover:text-slate-700 focus:outline-none hidden" aria-label="Tampilkan password">
                            <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    <p class="mt-2 text-xs text-slate-500">Gunakan minimal 8 karakter dengan huruf besar, kecil, dan angka</p>
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-2">Konfirmasi Password</label>
                    <div class="relative">
                        <input 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            type="password" 
                            required 
                            placeholder="Ketik ulang password"
                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 pr-12 text-slate-900 bg-slate-50 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100 placeholder-slate-400" 
                            autocomplete="new-password"
                        />
                        <button type="button" id="togglePasswordConfirm" class="absolute right-4 top-1/2 -translate-y-1/2 transform text-slate-500 hover:text-slate-700 focus:outline-none hidden" aria-label="Tampilkan konfirmasi password">
                            <svg id="eyeIconConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Password Strength Indicator -->
                <div id="passwordStrength" class="hidden">
                    <div class="flex gap-1 mb-2">
                        <div class="h-1.5 flex-1 rounded-full bg-slate-200" id="strength1"></div>
                        <div class="h-1.5 flex-1 rounded-full bg-slate-200" id="strength2"></div>
                        <div class="h-1.5 flex-1 rounded-full bg-slate-200" id="strength3"></div>
                        <div class="h-1.5 flex-1 rounded-full bg-slate-200" id="strength4"></div>
                    </div>
                    <p class="text-xs text-slate-600" id="strengthText">Kekuatan password</p>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-between gap-4 pt-2">
                    <a href="{{ route('login') }}" class="text-sm text-slate-600 hover:text-slate-900 hover:underline flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Kembali ke Login
                    </a>
                    <button 
                        type="submit" 
                        class="rounded-2xl bg-slate-900 px-6 py-3 text-white font-semibold hover:bg-slate-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        id="submitBtn"
                    >
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Password visibility toggle
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOpenIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
        const eyeClosedIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';

        const passwordConfirmInput = document.getElementById('password_confirmation');
        const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
        const eyeIconConfirm = document.getElementById('eyeIconConfirm');

        function updatePasswordToggle() {
            const hasPassword = passwordInput.value.length > 0;
            togglePassword.classList.toggle('hidden', !hasPassword);
            
            if (!hasPassword) {
                passwordInput.type = 'password';
            }
            
            eyeIcon.innerHTML = passwordInput.type === 'password' ? eyeOpenIcon : eyeClosedIcon;
        }

        function updatePasswordConfirmToggle() {
            const hasPassword = passwordConfirmInput.value.length > 0;
            togglePasswordConfirm.classList.toggle('hidden', !hasPassword);
            
            if (!hasPassword) {
                passwordConfirmInput.type = 'password';
            }
            
            eyeIconConfirm.innerHTML = passwordConfirmInput.type === 'password' ? eyeOpenIcon : eyeClosedIcon;
        }

        passwordInput.addEventListener('input', function() {
            updatePasswordToggle();
            checkPasswordStrength();
        });

        passwordConfirmInput.addEventListener('input', updatePasswordConfirmToggle);

        togglePassword.addEventListener('click', function(e) {
            e.preventDefault();
            passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
            updatePasswordToggle();
        });

        togglePasswordConfirm.addEventListener('click', function(e) {
            e.preventDefault();
            passwordConfirmInput.type = passwordConfirmInput.type === 'password' ? 'text' : 'password';
            updatePasswordConfirmToggle();
        });

        // Password strength checker
        function checkPasswordStrength() {
            const password = passwordInput.value;
            const strengthDiv = document.getElementById('passwordStrength');
            const strengthText = document.getElementById('strengthText');
            const bars = [
                document.getElementById('strength1'),
                document.getElementById('strength2'),
                document.getElementById('strength3'),
                document.getElementById('strength4')
            ];

            if (password.length === 0) {
                strengthDiv.classList.add('hidden');
                return;
            }

            strengthDiv.classList.remove('hidden');

            let strength = 0;
            let feedback = [];

            // Length check
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;

            // Character variety checks
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;

            // Cap at 4
            strength = Math.min(strength, 4);

            // Reset bars
            bars.forEach(bar => {
                bar.className = 'h-1.5 flex-1 rounded-full bg-slate-200';
            });

            // Color bars based on strength
            const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-emerald-500'];
            const texts = ['Sangat Lemah', 'Lemah', 'Sedang', 'Kuat'];
            const textColors = ['text-red-600', 'text-orange-600', 'text-yellow-600', 'text-emerald-600'];

            for (let i = 0; i < strength; i++) {
                bars[i].className = `h-1.5 flex-1 rounded-full ${colors[strength - 1]}`;
            }

            strengthText.className = `text-xs font-medium ${textColors[strength - 1] || 'text-slate-600'}`;
            strengthText.textContent = `Kekuatan password: ${texts[strength - 1] || 'Sangat Lemah'}`;
        }

        // Form submit handler
        const resetForm = document.getElementById('resetForm');
        const submitBtn = document.getElementById('submitBtn');

        resetForm.addEventListener('submit', function(e) {
            if (submitBtn.disabled) {
                e.preventDefault();
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            
            // Re-enable setelah 10 detik (fallback)
            setTimeout(function() {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Reset Password';
            }, 10000);
        });

        // Initialize
        updatePasswordToggle();
        updatePasswordConfirmToggle();
    </script>
</body>
</html>

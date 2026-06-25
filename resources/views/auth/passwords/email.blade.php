<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lupa Password - Sistem Absensi NFC Texmaco</title>
    @include('partials.favicon')
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-slate-100 text-slate-900 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="rounded-3xl bg-white border border-slate-200 p-8 shadow-lg">
            <!-- Logo -->
            <div class="mb-8 text-center">
                <img src="{{ asset('images/Texmaco Teks.jpeg') }}" alt="TEXMACO" class="mx-auto h-16 object-contain mb-4" />
                <h1 class="text-2xl font-bold text-slate-900">Lupa Password</h1>
                <p class="text-sm text-slate-600 mt-2">Masukkan email terdaftar untuk reset password</p>
            </div>

            <!-- Success Message -->
            @if (session('status'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 mb-6 flex items-start gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div>{{ session('status') }}</div>
                </div>
            @endif

            <!-- Info Message -->
            @if (session('info'))
                <div class="rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800 mb-6 flex items-start gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>{{ session('info') }}</div>
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 mb-6">
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
            <form action="{{ route('password.email') }}" method="POST" class="space-y-5" id="resetForm">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Alamat Email</label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        required 
                        placeholder="contoh@email.com"
                        value="{{ old('email') }}"
                        class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 bg-slate-50 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100 placeholder-slate-400" 
                        autocomplete="email" 
                    />
                    <p class="mt-2 text-xs text-slate-500">
                        <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        Link reset akan dikirim ke email ini jika terdaftar
                    </p>
                </div>

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
                        Kirim Link Reset
                    </button>
                </div>
            </form>

            <!-- Additional Help -->
            <div class="mt-6 pt-6 border-t border-slate-200 text-center">
                <p class="text-xs text-slate-500">
                    Tidak menerima email? Cek folder spam atau 
                    <a href="{{ route('password.contact-admin') }}" class="text-sky-600 hover:underline">hubungi administrator</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        // Auto-fill email dari localStorage jika ada
        window.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const savedEmail = localStorage.getItem('password_reset_email');
            
            if (savedEmail && !emailInput.value) {
                emailInput.value = savedEmail;
                // Clear localStorage setelah digunakan
                localStorage.removeItem('password_reset_email');
            }
        });

        // Handle form submit untuk mencegah double submit
        const resetForm = document.getElementById('resetForm');
        const submitBtn = document.getElementById('submitBtn');

        resetForm.addEventListener('submit', function(e) {
            if (submitBtn.disabled) {
                e.preventDefault();
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            
            // Re-enable setelah 15 detik (fallback jika submit gagal)
            setTimeout(function() {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Kirim Link Reset';
            }, 15000);
        });
    </script>
</body>
</html>

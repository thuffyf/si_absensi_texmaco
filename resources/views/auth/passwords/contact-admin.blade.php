<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hubungi Administrator - Sistem Absensi NFC Texmaco</title>
    @include('partials.favicon')
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-slate-100 text-slate-900 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="rounded-3xl bg-white border border-slate-200 p-8 shadow-lg">
            <!-- Icon -->
            <div class="mb-6 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-slate-900 mb-2">Reset Password Administrator</h1>
            </div>

            <!-- Info Message -->
            @if (session('info'))
                <div class="rounded-2xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800 mb-6">
                    {{ session('info') }}
                </div>
            @endif

            <!-- Content -->
            <div class="space-y-4 text-center">
                <p class="text-slate-700">
                    Untuk keamanan sistem, reset password akun <strong>Administrator</strong> dan <strong>Tata Usaha</strong> hanya dapat dilakukan oleh administrator sistem.
                </p>

                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-4 space-y-3">
                    <p class="text-sm font-medium text-slate-900">Silakan hubungi administrator melalui:</p>
                    
                    <div class="space-y-2 text-sm text-slate-700">
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>Email: <a href="mailto:admin@texmaco.local" class="text-sky-600 hover:underline font-medium">admin@texmaco.local</a></span>
                        </div>
                        
                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>Telepon/WhatsApp Kantor Sekolah</span>
                        </div>

                        <div class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Datang langsung ke ruang TU</span>
                        </div>
                    </div>
                </div>

                <p class="text-xs text-slate-500 pt-2">
                    Administrator akan membantu Anda mereset password dengan prosedur verifikasi identitas yang aman.
                </p>
            </div>

            <!-- Back Button -->
            <div class="mt-8 text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900 hover:underline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke halaman login
                </a>
            </div>
        </div>
    </div>
</body>
</html>

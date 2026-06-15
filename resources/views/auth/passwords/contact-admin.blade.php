<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Lupa Password - Instruksi</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-slate-100 flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white border border-slate-200 rounded-3xl p-8 shadow-lg text-center">
        <h1 class="text-2xl font-bold mb-4">Lupa Password</h1>
        <p class="text-sm text-slate-600 mb-6">Jika Anda lupa password, silakan hubungi administrator sistem untuk mereset kata sandi akun Anda.</p>
        <div class="space-y-2">
            <p class="text-sm">Email admin: <a href="mailto:admin@domain.local" class="text-sky-600 hover:underline">admin@domain.local</a></p>
            <p class="text-sm">Atau hubungi via nomor telepon/WA sekolah.</p>
        </div>
        <div class="mt-6">
            <a href="{{ route('login') }}" class="inline-block text-sm text-slate-700 hover:underline">Kembali ke halaman login</a>
        </div>
    </div>
</body>
</html>

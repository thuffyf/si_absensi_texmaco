<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Reset Password</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-slate-100 flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white border border-slate-200 rounded-3xl p-8 shadow-lg">
        <h1 class="text-lg font-semibold mb-4">Reset Password</h1>

        @if (session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 mb-4">{{ session('status') }}</div>
        @endif

        @if ($errors->has('email'))
            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 mb-4">{{ $errors->first('email') }}</div>
        @endif

        <p class="text-sm text-slate-600 mb-4">Masukkan alamat email terdaftar untuk menerima link reset password.</p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <input type="email" name="email" required placeholder="Alamat email" value="{{ old('email') }}"
                class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm mb-3 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" />
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('login') }}" class="text-sm text-slate-700 hover:underline">Kembali</a>
                <button type="submit" class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white">Kirim Link Reset</button>
            </div>
        </form>
    </div>
</body>
</html>

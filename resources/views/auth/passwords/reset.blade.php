<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Ubah Password</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-slate-100 flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white border border-slate-200 rounded-3xl p-8 shadow-lg">
        <h1 class="text-lg font-semibold mb-4">Ubah Password</h1>

        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <input type="email" name="email" required placeholder="Alamat email" value="{{ $email ?? old('email') }}"
                class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm mb-3 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" />

            <input type="password" name="password" required placeholder="Password baru"
                class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm mb-3 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" />

            <input type="password" name="password_confirmation" required placeholder="Konfirmasi password"
                class="w-full rounded-xl border border-slate-300 px-4 py-2 text-sm mb-3 focus:border-sky-500 focus:outline-none focus:ring-1 focus:ring-sky-500" />

            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('login') }}" class="text-sm text-slate-700 hover:underline">Kembali</a>
                <button type="submit" class="rounded-xl bg-sky-600 px-4 py-2 text-sm font-semibold text-white">Ubah Password</button>
            </div>
        </form>
    </div>
</body>
</html>

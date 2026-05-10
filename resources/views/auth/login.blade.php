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
            <div class= "flex flex-col justify-center px-6 py-10"">
                <div class="space-y-6">
                </div>
            </div>

            <!-- Right panel -->
            <div class="flex items-center justify-center">
                <div class="w-full max-w-md rounded-3xl bg-white border border-slate-200 p-8 shadow-lg">
                    <div class="mb-10 text-center">
                        <img src="{{ asset('images/Texmaco Teks.jpeg') }}" alt="TEXMACO" class="mx-auto h-20 object-contain" />
                    </div>

                    <form action="#" method="POST" class="space-y-5">
                        <div>
                            <input id="username" name="username" type="text" required placeholder="Username"
                                class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 bg-slate-50 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100 placeholder-slate-400" />
                        </div>

                        <div>
                            <input id="password" name="password" type="password" required placeholder="Password"
                                class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 bg-slate-50 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100 placeholder-slate-400" />
                        </div>

                        <div>
                            <select id="status" name="status" required
                                class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-slate-900 bg-slate-50 focus:border-sky-500 focus:outline-none focus:ring-2 focus:ring-sky-100">
                                <option value="" disabled selected>Silahkan pilih status</option>
                                <option value="siswa">SITEXA Siswa</option>
                                <option value="tata_usaha">SITEXA Tata Usaha</option>
                                <option value="guru">SITEXA Guru</option>
                            </select>
                        </div>

                        <div class="flex justify-center my-4">
                            <div class="g-recaptcha" data-sitekey="6LdtS-IsAAAAAGF0Z0mn2oP-1wy6dWEmKzaT2spq"></div>
                        </div>

                        <button type="submit" class="w-full rounded-2xl bg-slate-900 px-4 py-3 text-white font-bold text-lg hover:bg-slate-800 transition-colors">
                            MASUK
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- reCAPTCHA Script -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>

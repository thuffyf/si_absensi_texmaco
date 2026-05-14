@extends('layouts.app')

@section('title', 'Profil - SITEXA Absensi')
@section('page_title', 'Profil')
@section('page_subtitle', 'Kelola informasi pribadi Anda')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <!-- Profile Header -->
    <div class="glass-card p-8">
        <div class="flex items-center gap-6">
            <div class="relative">
                <img id="profile-preview" src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=eff6ff&color=0284c7' }}" alt="Profile Photo" class="w-24 h-24 rounded-full object-cover border-4 border-sky-200">
                <div class="absolute bottom-0 right-0 flex gap-2">
                    <button type="button" id="change-photo-btn" class="bg-sky-500 text-white p-2 rounded-full hover:bg-sky-600 transition-colors" title="Ubah Foto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </button>
                    @if(auth()->user()->photo)
                    <button type="button" id="delete-photo-btn" class="bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition-colors" title="Hapus Foto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                    @endif
                </div>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ auth()->user()->name }}</h1>
                <p class="text-slate-600">{{ auth()->user()->email }}</p>
                <p class="text-sm text-slate-500 mt-1">Tata Usaha</p>
            </div>
        </div>
    </div>

    <!-- Profile Form -->
    <div class="glass-card p-8">
        <h2 class="text-xl font-semibold text-slate-900 mb-6">Informasi Pribadi</h2>

        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Profile Photo Upload (Hidden) -->
            <input type="file" id="photo-input" name="photo" accept="image/*" class="hidden">
            @error('photo')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required
                    class="input-field">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                    class="input-field">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="btn-primary">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Logout Section -->
    <div class="glass-card p-8 border-t-2 border-red-200">
        <h2 class="text-xl font-semibold text-slate-900 mb-4">Keluar</h2>
        <p class="text-sm text-slate-600 mb-6">Klik tombol di bawah untuk keluar dari akun Anda.</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="px-6 py-2.5 bg-red-500 text-white font-medium rounded-lg hover:bg-red-600 transition-colors">
                🚪 Logout
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const changePhotoBtn = document.getElementById('change-photo-btn');
    const deletePhotoBtn = document.getElementById('delete-photo-btn');
    const photoInput = document.getElementById('photo-input');
    const profilePreview = document.getElementById('profile-preview');

    // Handle change photo button click
    changePhotoBtn.addEventListener('click', function() {
        photoInput.click();
    });

    // Handle file selection
    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                profilePreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Handle delete photo button click
    if (deletePhotoBtn) {
        deletePhotoBtn.addEventListener('click', function() {
            if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
                fetch('{{ url("/profile/delete-photo") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                })
                .catch(error => {
                    alert('Terjadi kesalahan saat menghapus foto.');
                    console.error('Error:', error);
                });
            }
        });
    }
});
</script>
@endsection
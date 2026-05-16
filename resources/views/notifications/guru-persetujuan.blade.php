@extends('layouts.app')

@section('title', 'Persetujuan Guru — SITEXA Absensi')
@section('page_title', 'Persetujuan dari Guru')
@section('page_subtitle', 'Izin & alpha — menunggu Terima atau Tolak')

@section('content')
@php
    $pending = [
        [
            'id' => '1',
            'jenis' => 'izin',
            'label' => 'Izin',
            'siswa' => 'Rafa Prakasa',
            'nis' => '12001',
            'kelas' => 'XII TEI',
            'guru' => 'Drs. Bambang Wijaya, M.Pd.',
            'mapel' => 'Bahasa Indonesia',
            'tanggal' => '12 Mei 2026',
            'keterangan' => 'Izin acara keluarga ke luar kota, karena sudah ada janji ziarah bersama keluarga',
        ],
        [
            'id' => '2',
            'jenis' => 'alpha',
            'label' => 'Alpha (tidak hadir)',
            'siswa' => 'Silvi Lestari',
            'nis' => '12002',
            'kelas' => 'XII TEI',
            'guru' => 'Fitriani, S.Pd.',
            'mapel' => 'Matematika',
            'tanggal' => '12 Mei 2026',
            'keterangan' => 'Siswa tidak masuk tanpa keterangan di kelas pagi. Orang tua belum menghubungi wali kelas; mohon verifikasi kehadiran di gerbang NFC.',
        ],
        [
            'id' => '3',
            'jenis' => 'izin',
            'label' => 'Izin',
            'siswa' => 'Adi Pratama',
            'nis' => '12003',
            'kelas' => 'XII TEI',
            'guru' => 'Hendra Kusuma, S.Pd.',
            'mapel' => 'Fisika',
            'tanggal' => '11 Mei 2026',
            'keterangan' => 'Izin mengikuti kegiatan OSIS luar sekolah dengan surat tugas dari pembina OSIS.',
        ],
    ];
@endphp

<div class="mx-auto max-w-4xl space-y-5 pb-8">
    <p class="text-sm text-slate-600">
        Berikut laporan dari guru yang memerlukan keputusan Tata Usaha. Baca keterangan guru, lalu pilih <strong class="text-slate-900">Terima</strong> atau <strong class="text-slate-900">Tolak</strong>.
        (Contoh tampilan — nanti dapat dihubungkan ke database dan API.)
    </p>

    <div class="space-y-5" id="approval-list">
        @foreach ($pending as $item)
            <article
                class="approval-card rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md"
                data-id="{{ $item['id'] }}"
            >
                <div class="flex flex-wrap items-start justify-between gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <span @class([
                            'inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide',
                            'bg-amber-100 text-amber-900' => $item['jenis'] === 'izin',
                            'bg-rose-100 text-rose-900' => $item['jenis'] === 'alpha',
                        ])>{{ $item['label'] }}</span>
                        <h2 class="mt-3 text-xl font-bold text-slate-900">{{ $item['siswa'] }}</h2>
                        <p class="mt-1 text-sm text-slate-500">NIS {{ $item['nis'] }} · {{ $item['kelas'] }}</p>
                    </div>
                    <div class="text-right text-sm text-slate-600">
                        <p class="font-semibold text-slate-800">{{ $item['guru'] }}</p>
                        <p class="text-slate-500">{{ $item['mapel'] }}</p>
                        <p class="mt-1 text-xs text-slate-400">{{ $item['tanggal'] }}</p>
                    </div>
                </div>

                <div class="mt-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Keterangan dari guru</p>
                    <blockquote class="mt-2 rounded-2xl border border-slate-100 bg-slate-50 px-4 py-3 text-base leading-relaxed text-slate-800">
                        “{{ $item['keterangan'] }}”
                    </blockquote>
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <button
                        type="button"
                        class="btn-approve inline-flex flex-1 min-w-[8rem] items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white shadow-sm hover:bg-emerald-700 sm:flex-none"
                        data-action="terima"
                    >
                        Terima
                    </button>
                    <button
                        type="button"
                        class="btn-reject inline-flex flex-1 min-w-[8rem] items-center justify-center rounded-2xl border-2 border-slate-300 bg-white px-5 py-3 text-sm font-bold text-slate-800 hover:border-rose-300 hover:bg-rose-50 sm:flex-none"
                        data-action="tolak"
                    >
                        Tolak
                    </button>
                </div>
            </article>
        @endforeach
    </div>

    <p id="empty-approvals" class="hidden rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-4 py-8 text-center text-sm text-slate-500">
        Tidak ada notifikasi yang menunggu persetujuan.
    </p>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var list = document.getElementById('approval-list');
        var empty = document.getElementById('empty-approvals');
        if (!list) return;

        list.addEventListener('click', function (e) {
            var btn = e.target.closest('.btn-approve, .btn-reject');
            if (!btn) return;
            var card = btn.closest('.approval-card');
            if (!card) return;
            var action = btn.getAttribute('data-action') === 'terima' ? 'diterima' : 'ditolak';
            if (window.DashboardUtils && typeof window.DashboardUtils.showToast === 'function') {
                window.DashboardUtils.showToast('Pengajuan ' + action + ' (contoh).', action === 'diterima' ? 'success' : 'info');
            }
            card.style.opacity = '0.6';
            card.style.pointerEvents = 'none';
            setTimeout(function () {
                card.remove();
                if (list.children.length === 0) {
                    empty.classList.remove('hidden');
                }
            }, 350);
        });
    });
</script>
@endsection

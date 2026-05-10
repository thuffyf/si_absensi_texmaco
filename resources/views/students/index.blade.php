@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="mb-8 animate-fade-in">
    <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
        <div>
            <h1 class="text-4xl font-bold text-gradient mb-2">👥 Data Siswa</h1>
            <p class="text-gray-400">Kelola data siswa dan status NFC</p>
        </div>
        <button class="btn-primary">
            + Tambah Siswa
        </button>
    </div>
</div>

<!-- Filter & Search -->
<div class="glass-card p-6 rounded-2xl mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Search -->
        <div class="lg:col-span-2">
            <input type="text" class="input-field w-full" placeholder="🔍 Cari nama, NIM, atau kelas..." />
        </div>

        <!-- Filter Kelas -->
        <select class="input-field text-sm">
            <option>Semua Kelas</option>
            <option>XII IPA 1</option>
            <option>XII IPA 2</option>
            <option>XII IPS 1</option>
            <option>XII IPS 2</option>
            <option>XI IPA 1</option>
            <option>XI IPA 2</option>
        </select>

        <!-- Filter Status -->
        <select class="input-field text-sm">
            <option>Semua Status</option>
            <option>Aktif</option>
            <option>Tidak Aktif</option>
            <option>Lulus</option>
        </select>

        <!-- Filter NFC -->
        <select class="input-field text-sm">
            <option>Semua NFC</option>
            <option>Kartu NFC</option>
            <option>HP/Handphone</option>
            <option>Belum Terdaftar</option>
        </select>
    </div>

    <!-- Filter Actions -->
    <div class="flex gap-2 mt-4">
        <button class="btn-secondary text-sm">
            🔄 Reset Filter
        </button>
        <button class="btn-secondary text-sm">
            📥 Import CSV
        </button>
        <button class="btn-secondary text-sm">
            📤 Export Excel
        </button>
    </div>
</div>

<!-- Data Table -->
<div class="glass-card p-6 rounded-2xl overflow-x-auto">
    <table class="data-table">
        <thead>
            <tr>
                <th class="w-12">
                    <input type="checkbox" class="w-4 h-4 rounded border-neon-cyan/30 accent-neon-cyan cursor-pointer" />
                </th>
                <th>Foto & Nama</th>
                <th>NIM / NIS</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>Status</th>
                <th>NFC</th>
                <th class="text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <!-- Row 1 -->
            <tr>
                <td><input type="checkbox" class="w-4 h-4 rounded border-neon-cyan/30 accent-neon-cyan cursor-pointer" /></td>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-neon-cyan to-neon-blue flex items-center justify-center font-bold shadow-glow-cyan-sm">👨</div>
                        <div>
                            <p class="font-semibold text-white">Rafa Prakasa</p>
                            <p class="text-xs text-gray-400">rafa.prakasa@student.com</p>
                        </div>
                    </div>
                </td>
                <td><span class="font-mono text-neon-cyan">12001</span></td>
                <td>XII IPA 1</td>
                <td>IPA</td>
                <td><span class="badge-success">Aktif</span></td>
                <td><span class="badge-neon">Kartu NFC</span></td>
                <td class="text-right">
                    <div class="flex justify-end gap-2">
                        <button class="btn-icon text-sm" title="View">👁️</button>
                        <button class="btn-icon text-sm" title="Edit">✏️</button>
                        <button class="btn-icon text-sm" title="Delete">🗑️</button>
                    </div>
                </td>
            </tr>

            <!-- Row 2 -->
            <tr>
                <td><input type="checkbox" class="w-4 h-4 rounded border-neon-cyan/30 accent-neon-cyan cursor-pointer" /></td>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-400 to-neon-purple flex items-center justify-center font-bold shadow-glow-cyan-sm">👩</div>
                        <div>
                            <p class="font-semibold text-white">Silvi Lestari</p>
                            <p class="text-xs text-gray-400">silvi.lestari@student.com</p>
                        </div>
                    </div>
                </td>
                <td><span class="font-mono text-neon-cyan">12002</span></td>
                <td>XII IPA 1</td>
                <td>IPA</td>
                <td><span class="badge-success">Aktif</span></td>
                <td><span class="badge-neon">Kartu NFC</span></td>
                <td class="text-right">
                    <div class="flex justify-end gap-2">
                        <button class="btn-icon text-sm">👁️</button>
                        <button class="btn-icon text-sm">✏️</button>
                        <button class="btn-icon text-sm">🗑️</button>
                    </div>
                </td>
            </tr>

            <!-- Row 3 -->
            <tr>
                <td><input type="checkbox" class="w-4 h-4 rounded border-neon-cyan/30 accent-neon-cyan cursor-pointer" /></td>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-600 flex items-center justify-center font-bold shadow-glow-cyan-sm">👨</div>
                        <div>
                            <p class="font-semibold text-white">Adi Pratama</p>
                            <p class="text-xs text-gray-400">adi.pratama@student.com</p>
                        </div>
                    </div>
                </td>
                <td><span class="font-mono text-neon-cyan">12003</span></td>
                <td>XII IPA 1</td>
                <td>IPA</td>
                <td><span class="badge-success">Aktif</span></td>
                <td><span class="badge-info">HP/Handphone</span></td>
                <td class="text-right">
                    <div class="flex justify-end gap-2">
                        <button class="btn-icon text-sm">👁️</button>
                        <button class="btn-icon text-sm">✏️</button>
                        <button class="btn-icon text-sm">🗑️</button>
                    </div>
                </td>
            </tr>

            <!-- Row 4 -->
            <tr>
                <td><input type="checkbox" class="w-4 h-4 rounded border-neon-cyan/30 accent-neon-cyan cursor-pointer" /></td>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-400 to-rose-500 flex items-center justify-center font-bold shadow-glow-cyan-sm">👩</div>
                        <div>
                            <p class="font-semibold text-white">Mira Putri</p>
                            <p class="text-xs text-gray-400">mira.putri@student.com</p>
                        </div>
                    </div>
                </td>
                <td><span class="font-mono text-neon-cyan">12004</span></td>
                <td>XII IPA 2</td>
                <td>IPA</td>
                <td><span class="badge-success">Aktif</span></td>
                <td><span class="badge-neon">Kartu NFC</span></td>
                <td class="text-right">
                    <div class="flex justify-end gap-2">
                        <button class="btn-icon text-sm">👁️</button>
                        <button class="btn-icon text-sm">✏️</button>
                        <button class="btn-icon text-sm">🗑️</button>
                    </div>
                </td>
            </tr>

            <!-- Row 5 -->
            <tr>
                <td><input type="checkbox" class="w-4 h-4 rounded border-neon-cyan/30 accent-neon-cyan cursor-pointer" /></td>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center font-bold shadow-glow-cyan-sm">👨</div>
                        <div>
                            <p class="font-semibold text-white">Danu Wijaya</p>
                            <p class="text-xs text-gray-400">danu.wijaya@student.com</p>
                        </div>
                    </div>
                </td>
                <td><span class="font-mono text-neon-cyan">12005</span></td>
                <td>XII IPA 2</td>
                <td>IPA</td>
                <td><span class="badge-success">Aktif</span></td>
                <td><span class="badge-warning">Belum Terdaftar</span></td>
                <td class="text-right">
                    <div class="flex justify-end gap-2">
                        <button class="btn-icon text-sm">👁️</button>
                        <button class="btn-icon text-sm">✏️</button>
                        <button class="btn-icon text-sm">🗑️</button>
                    </div>
                </td>
            </tr>

            <!-- Row 6 -->
            <tr>
                <td><input type="checkbox" class="w-4 h-4 rounded border-neon-cyan/30 accent-neon-cyan cursor-pointer" /></td>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-600 flex items-center justify-center font-bold shadow-glow-cyan-sm">👨</div>
                        <div>
                            <p class="font-semibold text-white">Budi Santoso</p>
                            <p class="text-xs text-gray-400">budi.santoso@student.com</p>
                        </div>
                    </div>
                </td>
                <td><span class="font-mono text-neon-cyan">12006</span></td>
                <td>XII IPS 1</td>
                <td>IPS</td>
                <td><span class="badge-success">Aktif</span></td>
                <td><span class="badge-neon">Kartu NFC</span></td>
                <td class="text-right">
                    <div class="flex justify-end gap-2">
                        <button class="btn-icon text-sm">👁️</button>
                        <button class="btn-icon text-sm">✏️</button>
                        <button class="btn-icon text-sm">🗑️</button>
                    </div>
                </td>
            </tr>

            <!-- Row 7 -->
            <tr>
                <td><input type="checkbox" class="w-4 h-4 rounded border-neon-cyan/30 accent-neon-cyan cursor-pointer" /></td>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-pink-400 to-pink-600 flex items-center justify-center font-bold shadow-glow-cyan-sm">👩</div>
                        <div>
                            <p class="font-semibold text-white">Ani Wijaya</p>
                            <p class="text-xs text-gray-400">ani.wijaya@student.com</p>
                        </div>
                    </div>
                </td>
                <td><span class="font-mono text-neon-cyan">12007</span></td>
                <td>XII IPS 1</td>
                <td>IPS</td>
                <td><span class="badge-success">Aktif</span></td>
                <td><span class="badge-info">HP/Handphone</span></td>
                <td class="text-right">
                    <div class="flex justify-end gap-2">
                        <button class="btn-icon text-sm">👁️</button>
                        <button class="btn-icon text-sm">✏️</button>
                        <button class="btn-icon text-sm">🗑️</button>
                    </div>
                </td>
            </tr>

            <!-- Row 8 -->
            <tr>
                <td><input type="checkbox" class="w-4 h-4 rounded border-neon-cyan/30 accent-neon-cyan cursor-pointer" /></td>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center font-bold shadow-glow-cyan-sm">👨</div>
                        <div>
                            <p class="font-semibold text-white">Citra Kusuma</p>
                            <p class="text-xs text-gray-400">citra.kusuma@student.com</p>
                        </div>
                    </div>
                </td>
                <td><span class="font-mono text-neon-cyan">12008</span></td>
                <td>XI IPA 1</td>
                <td>IPA</td>
                <td><span class="badge-success">Aktif</span></td>
                <td><span class="badge-neon">Kartu NFC</span></td>
                <td class="text-right">
                    <div class="flex justify-end gap-2">
                        <button class="btn-icon text-sm">👁️</button>
                        <button class="btn-icon text-sm">✏️</button>
                        <button class="btn-icon text-sm">🗑️</button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="flex items-center justify-between mt-6 pt-6 border-t border-neon-cyan/10">
        <div class="text-sm text-gray-400">
            Menampilkan <span class="text-neon-cyan font-bold">1-8</span> dari <span class="text-neon-cyan font-bold">340</span> siswa
        </div>
        <div class="flex items-center gap-2">
            <button class="px-3 py-2 rounded-lg glass-effect hover:bg-neon-cyan/10 text-sm">← Sebelumnya</button>
            <button class="px-3 py-2 rounded-lg bg-neon-cyan text-dark-bg font-bold text-sm">1</button>
            <button class="px-3 py-2 rounded-lg glass-effect hover:bg-neon-cyan/10 text-sm">2</button>
            <button class="px-3 py-2 rounded-lg glass-effect hover:bg-neon-cyan/10 text-sm">3</button>
            <span class="text-gray-400">...</span>
            <button class="px-3 py-2 rounded-lg glass-effect hover:bg-neon-cyan/10 text-sm">Selanjutnya →</button>
        </div>
    </div>
</div>
@endsection

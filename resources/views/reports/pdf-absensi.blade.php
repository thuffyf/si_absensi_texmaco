<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0 0 10px 0;
            color: #1e40af;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .filter-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f3f4f6;
            border-radius: 5px;
        }
        .filter-info p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #1e40af;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .status-hadir {
            background-color: #d1fae5;
            color: #065f46;
            padding: 2px 8px;
            border-radius: 3px;
            font-weight: bold;
        }
        .status-izin {
            background-color: #fef3c7;
            color: #92400e;
            padding: 2px 8px;
            border-radius: 3px;
            font-weight: bold;
        }
        .status-sakit {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 2px 8px;
            border-radius: 3px;
            font-weight: bold;
        }
        .status-alpha {
            background-color: #f3f4f6;
            color: #374151;
            padding: 2px 8px;
            border-radius: 3px;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .summary {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #eff6ff;
            border-radius: 5px;
            border-left: 4px solid #1e40af;
        }
        .summary h3 {
            margin: 0 0 10px 0;
            color: #1e40af;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }
        .summary-item {
            text-align: center;
            padding: 10px;
            background-color: white;
            border-radius: 5px;
        }
        .summary-item .count {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
        }
        .summary-item .label {
            font-size: 11px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Absensi Siswa</h1>
        <p>SITEXA Absensi - Sistem Informasi Texmaco</p>
        <p>Tanggal Cetak: {{ date('d F Y H:i') }}</p>
    </div>

    @if($startDate || $endDate || $status || $className)
    <div class="filter-info">
        <strong>Filter Aktif:</strong>
        @if($startDate)<p>Tanggal Mulai: {{ $startDate }}</p>@endif
        @if($endDate)<p>Tanggal Akhir: {{ $endDate }}</p>@endif
        @if($status)<p>Status: {{ ucfirst($status) }}</p>@endif
        @if($className)<p>Kelas: {{ $className }}</p>@endif
    </div>
    @endif

    <div class="summary">
        <h3>Ringkasan Absensi</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="count">{{ $attendances->where('status', 'hadir')->count() }}</div>
                <div class="label">Hadir</div>
            </div>
            <div class="summary-item">
                <div class="count">{{ $attendances->where('status', 'izin')->count() }}</div>
                <div class="label">Izin</div>
            </div>
            <div class="summary-item">
                <div class="count">{{ $attendances->where('status', 'sakit')->count() }}</div>
                <div class="label">Sakit</div>
            </div>
            <div class="summary-item">
                <div class="count">{{ $attendances->where('status', 'alpha')->count() }}</div>
                <div class="label">Alpha</div>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>NIS</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $index => $attendance)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $attendance->student?->name ?? '-' }}</td>
                <td>{{ $attendance->student?->nis ?? '-' }}</td>
                <td>{{ $attendance->student?->class_name ?? '-' }}</td>
                <td>{{ $attendance->student?->major ?? '-' }}</td>
                <td>{{ $attendance->attendance_date?->format('d F Y') ?? '-' }}</td>
                <td>{{ $attendance->attendance_time ?? '-' }}</td>
                <td><span class="status-{{ $attendance->status }}">{{ ucfirst($attendance->status) }}</span></td>
                <td>{{ $attendance->note ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Total Data: {{ $attendances->count() }} Record</p>
        <p>Dokumen ini dihasilkan secara otomatis oleh SITEXA Absensi</p>
    </div>
</body>
</html>

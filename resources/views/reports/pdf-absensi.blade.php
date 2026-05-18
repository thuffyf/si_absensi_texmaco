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



    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>NIS</th>
                <th>Kelas</th>
                <th>Hadir</th>
                <th>Izin</th>
                <th>Sakit</th>
                <th>Alpha</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row['student']->name ?? '-' }}</td>
                <td>{{ $row['student']->nis ?? '-' }}</td>
                <td>{{ $row['student']->class_name ?? '-' }}</td>
                <td style="text-align: center;"><span class="status-hadir">{{ $row['hadir'] }}</span></td>
                <td style="text-align: center;"><span class="status-izin">{{ $row['izin'] }}</span></td>
                <td style="text-align: center;"><span class="status-sakit">{{ $row['sakit'] }}</span></td>
                <td style="text-align: center;"><span class="status-alpha">{{ $row['alpha'] }}</span></td>
                <td style="text-align: center; font-weight: bold;">{{ $row['total'] }}</td>
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

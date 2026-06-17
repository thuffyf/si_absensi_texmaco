<?php
/**
 * Check Attendance Today
 * Run: php check_attendance_today.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Attendance;
use App\Models\Student;
use Carbon\Carbon;

echo "=== CHECK ATTENDANCE TODAY ===\n\n";

$today = Carbon::today('Asia/Jakarta')->toDateString();
$now = Carbon::now('Asia/Jakarta');

echo "Tanggal hari ini: {$today}\n";
echo "Waktu sekarang: {$now}\n\n";

// 1. Total siswa
$totalStudents = Student::where('major', 'TEI')->count();
echo "1. Total siswa TEI: {$totalStudents}\n\n";

// 2. Total attendance records hari ini
$allAttendanceToday = Attendance::whereDate('attendance_date', $today)->get();
echo "2. Total attendance records hari ini: {$allAttendanceToday->count()}\n\n";

// 3. Detail attendance records
if ($allAttendanceToday->isNotEmpty()) {
    echo "3. Detail attendance records:\n";
    foreach ($allAttendanceToday as $att) {
        $student = $att->student;
        echo "   - Student: " . ($student ? $student->name : 'Unknown') . "\n";
        echo "     NIS: " . ($student ? $student->nis : '-') . "\n";
        echo "     Major: " . ($student ? $student->major : '-') . "\n";
        echo "     Date: {$att->attendance_date}\n";
        echo "     Time: {$att->attendance_time}\n";
        echo "     Status: {$att->status}\n\n";
    }
}

// 4. Attendance dengan time valid (tidak 00:00:00)
$validTimeAttendance = Attendance::whereDate('attendance_date', $today)
    ->whereNotNull('attendance_time')
    ->where('attendance_time', '!=', '00:00:00')
    ->get();

echo "4. Attendance dengan valid time (bukan 00:00:00): {$validTimeAttendance->count()}\n\n";

// 5. Attendance untuk siswa TEI saja
$teiAttendance = Attendance::whereDate('attendance_date', $today)
    ->whereHas('student', function ($query) {
        $query->where('major', 'TEI');
    })
    ->get();

echo "5. Attendance siswa TEI hari ini: {$teiAttendance->count()}\n\n";

// 6. Attendance siswa TEI dengan valid time
$teiValidAttendance = Attendance::whereDate('attendance_date', $today)
    ->whereNotNull('attendance_time')
    ->where('attendance_time', '!=', '00:00:00')
    ->whereHas('student', function ($query) {
        $query->where('major', 'TEI');
    })
    ->get();

echo "6. Attendance siswa TEI dengan valid time: {$teiValidAttendance->count()}\n\n";

// 7. Status breakdown
echo "7. Status breakdown (TEI valid time):\n";
$statusCounts = $teiValidAttendance->groupBy('status')->map->count();
foreach ($statusCounts as $status => $count) {
    echo "   - {$status}: {$count}\n";
}
echo "\n";

// 8. Cek beberapa sample attendance
echo "8. Sample 5 attendance records hari ini (TEI):\n";
$samples = $teiAttendance->take(5);
foreach ($samples as $att) {
    $student = $att->student;
    echo "   [{$att->id}] " . ($student ? $student->name : 'Unknown') . 
         " - {$att->attendance_time} - {$att->status}\n";
}
echo "\n";

// 9. Query yang digunakan di DashboardController
echo "9. Simulasi query DashboardController:\n";
$dashboardQuery = Attendance::query()
    ->with('student')
    ->whereDate('attendance_date', $today)
    ->whereHas('student', function ($query) {
        $query->where('major', 'TEI');
    })
    ->get();

$dashboardValid = $dashboardQuery->filter(function ($att) {
    return $att->attendance_time && $att->attendance_time !== '00:00:00';
});

echo "   Total query: {$dashboardQuery->count()}\n";
echo "   Setelah filter valid time: {$dashboardValid->count()}\n\n";

// 10. Query yang digunakan di MonitoringController
echo "10. Simulasi query MonitoringController:\n";
$monitoringCount = Attendance::query()
    ->whereDate('attendance_date', $today)
    ->whereNotNull('attendance_time')
    ->where('attendance_time', '!=', '00:00:00')
    ->count();

echo "   Total attendance (monitoring): {$monitoringCount}\n\n";

echo "=== SUMMARY ===\n";
echo "Dashboard seharusnya menampilkan: {$dashboardValid->count()} hadir\n";
echo "Monitoring seharusnya menampilkan: {$monitoringCount} total scans\n";
echo "\n";

if ($dashboardValid->count() === 0 && $monitoringCount === 0) {
    echo "⚠️ TIDAK ADA DATA ABSENSI HARI INI!\n";
    echo "Kemungkinan penyebab:\n";
    echo "1. Belum ada siswa yang tap kartu NFC hari ini\n";
    echo "2. Device NFC belum mengirim data\n";
    echo "3. Tanggal sistem tidak sinkron\n\n";
    
    echo "Untuk test, coba tap kartu NFC atau jalankan:\n";
    echo "php artisan tinker\n";
    echo ">> use App\\Models\\Attendance;\n";
    echo ">> use App\\Models\\Student;\n";
    echo ">> use Carbon\\Carbon;\n";
    echo ">> \$student = Student::first();\n";
    echo ">> Attendance::create(['student_id' => \$student->id, 'attendance_date' => Carbon::today('Asia/Jakarta'), 'attendance_time' => Carbon::now('Asia/Jakarta')->format('H:i:s'), 'status' => 'hadir']);\n";
}

echo "=== END ===\n";

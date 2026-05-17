<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Students table - revert to English
        Schema::table('students', function (Blueprint $table) {
            $table->renameColumn('nama_siswa', 'name');
            $table->renameColumn('nim', 'nis');
            $table->renameColumn('tanggal_lahir_siswa', 'date_of_birth');
            $table->renameColumn('kelas', 'class_name');
            $table->renameColumn('jurusan', 'major');
            $table->renameColumn('no_telpon', 'phone');
            $table->renameColumn('id_kartu', 'uid_kartu');
            $table->renameColumn('status_aktif_siswa', 'status');
            $table->renameColumn('status_nfc', 'nfc_type');
        });

        // Teachers table - revert to English
        Schema::table('teachers', function (Blueprint $table) {
            $table->renameColumn('nama_guru', 'name');
            $table->renameColumn('mata_pelajaran', 'subject');
            $table->renameColumn('no_telpon', 'phone');
            $table->renameColumn('tanggal_lahir_guru', 'date_of_birth');
            $table->renameColumn('status_aktif', 'status');
            
            // Remove jenis_kelamin field if it exists
            if (Schema::hasColumn('teachers', 'jenis_kelamin')) {
                $table->dropColumn('jenis_kelamin');
            }
        });

        // Schedules table - revert to English
        Schema::table('schedules', function (Blueprint $table) {
            $table->renameColumn('kelas', 'class_name');
            $table->renameColumn('mata_pelajaran', 'subject');
            $table->renameColumn('jumlah_siswa', 'total_students');
            $table->renameColumn('status_jadwal', 'status');
        });

        // NfcDevices table - revert to English
        Schema::table('nfc_devices', function (Blueprint $table) {
            $table->renameColumn('nama_alat', 'name');
            $table->renameColumn('status_alat', 'status');
            $table->renameColumn('lokasi', 'location');
        });

        // Attendances table - revert to English
        Schema::table('attendances', function (Blueprint $table) {
            $table->renameColumn('tanggal_absen', 'attendance_date');
            $table->renameColumn('waktu_absen', 'attendance_time');
            $table->renameColumn('keterangan', 'status');
            $table->renameColumn('catatan', 'note');
        });

        // LeaveRequests table - revert to English
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->renameColumn('jenis_izin', 'type');
            $table->renameColumn('tanggal_mulai', 'start_date');
            $table->renameColumn('tanggal_selesai', 'end_date');
            $table->renameColumn('keterangan', 'reason');
            $table->renameColumn('status_request', 'status');
            $table->renameColumn('tanggal_request', 'requested_at');
            $table->renameColumn('tanggal_respon', 'responded_at');
            $table->renameColumn('catatan_respon', 'response_note');
        });
    }

    public function down(): void
    {
        // This is the revert migration, so down() would re-apply Indonesian names
        // But since we're reverting, we don't need to implement down()
    }
};

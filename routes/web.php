<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Admin Dashboard Routes untuk Sistem Absensi NFC Texmaco
|
*/

// Login Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Dashboard Routes (Protected by auth middleware)
Route::middleware(['auth'])->group(function () {
    // Dashboard Utama
    Route::get('/', function () {
        return view('dashboard.index');
    })->name('dashboard');
    
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    });

    // Monitoring NFC Real-Time
    Route::get('/monitoring/nfc', function () {
        return view('monitoring.nfc');
    })->name('monitoring.nfc');

    // Data Siswa
    Route::get('/siswa', function () {
        return view('students.index');
    })->name('students.index');

    // Data Guru
    Route::get('/guru', function () {
        return view('teachers.index');
    })->name('teachers.index');

    // Jadwal Kelas
    Route::get('/jadwal', function () {
        return view('schedules.index');
    })->name('schedules.index');

    // Request Izin & Sakit
    Route::get('/request-izin-sakit', function () {
        return view('requests.izin-sakit');
    })->name('requests.izin-sakit');

    // Laporan Absensi
    Route::get('/laporan/absensi', function () {
        return view('reports.absensi');
    })->name('reports.absensi');

    // Monitoring Alat NFC
    Route::get('/alat-nfc', function () {
        return view('devices.nfc-tools');
    })->name('devices.nfc-tools');

    // Pengaturan Sistem
    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings.index');
});


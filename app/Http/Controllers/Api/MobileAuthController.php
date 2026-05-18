<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentDevice;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class MobileAuthController extends Controller
{
    public function loginStudent(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $student = Student::where('username', $data['username'])->first();

        if (!$student || !$student->password) {
            return response()->json(['message' => 'Akun siswa tidak valid.'], 401);
        }

        if (!Hash::check($data['password'], $student->password)) {
            return response()->json(['message' => 'Username atau password salah.'], 401);
        }

        if (!$student->uid_kartu) {
            return response()->json(['message' => 'UID siswa belum diatur oleh admin.'], 422);
        }

        $token = Str::random(40);
        $student->api_token = hash('sha256', $token);
        $student->save();

        return response()->json([
            'message' => 'Login siswa berhasil.',
            'token' => $token,
            'role' => 'siswa',
            'user' => [
                'name' => $student->name,
                'nis' => $student->nis,
                'class_name' => $student->class_name,
                'uid_kartu' => $student->uid_kartu,
            ],
            'uid_kartu' => $student->uid_kartu,
        ]);
    }

    public function loginTeacher(Request $request)
    {
        $data = $request->validate([
            'nip' => 'required|string',
            'birth_date' => 'required|date',
        ]);

        $teacher = Teacher::where('nip', $data['nip'])->first();

        if (!$teacher || !$teacher->date_of_birth) {
            return response()->json(['message' => 'Data guru tidak valid.'], 401);
        }

        if ($teacher->date_of_birth->toDateString() !== $data['birth_date']) {
            return response()->json(['message' => 'Tanggal lahir tidak cocok.'], 401);
        }

        $token = Str::random(40);
        $teacher->api_token = hash('sha256', $token);
        $teacher->save();

        return response()->json([
            'message' => 'Login guru berhasil.',
            'token' => $token,
            'role' => 'guru',
            'user' => [
                'name' => $teacher->name,
                'nip' => $teacher->nip,
            ],
        ]);
    }

    public function registerDevice(Request $request)
    {
        $data = $request->validate([
            'student_code' => 'required|string',
            'token' => 'required|string',
            'device_label' => 'nullable|string|max:120',
            'platform' => 'nullable|string|max:30',
        ]);

        $student = Student::where('nis', $data['student_code'])->first();

        if (!$student) {
            return response()->json(['message' => 'Siswa tidak ditemukan.'], 404);
        }

        $existing = StudentDevice::where('device_token', $data['token'])->first();
        if ($existing && $existing->student_id !== $student->id) {
            return response()->json(['message' => 'Token sudah terdaftar untuk siswa lain.'], 409);
        }

        $device = StudentDevice::updateOrCreate(
            ['device_token' => $data['token']],
            [
                'student_id' => $student->id,
                'device_label' => $data['device_label'] ?? null,
                'platform' => $data['platform'] ?? 'android',
                'last_seen_at' => now(),
            ]
        );

        return response()->json([
            'message' => 'Perangkat berhasil didaftarkan.',
            'device' => [
                'id' => $device->id,
                'student_id' => $student->id,
            ],
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nim', 'like', '%' . $search . '%')
                    ->orWhere('class_name', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('class')) {
            $query->where('class_name', $request->string('class'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('nfc')) {
            $query->where('nfc_type', $request->string('nfc'));
        }

        $students = $query->orderBy('name')->paginate(12)->withQueryString();

        return view('students.index', compact('students'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nim' => 'required|string|max:32|unique:students,nim',
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:100|unique:students,username',
            'email' => 'nullable|email|max:255',
            'password' => 'nullable|string|min:6',
            'date_of_birth' => 'nullable|date',
            'class_name' => 'required|string|max:100',
            'major' => 'nullable|string|max:100',
            'status' => 'required|in:aktif,tidak_aktif,lulus',
            'nfc_type' => 'required|in:kartu,handphone,belum_terdaftar',
            'uid_kartu' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:50',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        Student::create($data);

        return back()->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'nim' => 'required|string|max:32|unique:students,nim,' . $student->id,
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:100|unique:students,username,' . $student->id,
            'email' => 'nullable|email|max:255',
            'password' => 'nullable|string|min:6',
            'date_of_birth' => 'nullable|date',
            'class_name' => 'required|string|max:100',
            'major' => 'nullable|string|max:100',
            'status' => 'required|in:aktif,tidak_aktif,lulus',
            'nfc_type' => 'required|in:kartu,handphone,belum_terdaftar',
            'uid_kartu' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:50',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $student->update($data);

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return back()->with('success', 'Data siswa berhasil dihapus.');
    }
}

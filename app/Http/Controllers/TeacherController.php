<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::query();

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nip', 'like', '%' . $search . '%')
                    ->orWhere('subject', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('subject')) {
            $query->where('subject', $request->string('subject'));
        }

        $teachers = $query->orderBy('name')->paginate(12)->withQueryString();

        return view('teachers.index', compact('teachers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nip' => 'required|string|max:32|unique:teachers,nip',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'password' => 'nullable|string|min:6',
            'role' => 'nullable|string|max:100',
            'subject' => 'nullable|string|max:120',
            'phone' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'status' => 'required|in:aktif,cuti,non_aktif',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        Teacher::create($data);

        return back()->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function edit(Teacher $teacher)
    {
        return view('teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $data = $request->validate([
            'nip' => 'required|string|max:32|unique:teachers,nip,' . $teacher->id,
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'password' => 'nullable|string|min:6',
            'role' => 'nullable|string|max:100',
            'subject' => 'nullable|string|max:120',
            'phone' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'status' => 'required|in:aktif,cuti,non_aktif',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $teacher->update($data);

        return redirect()->route('teachers.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return back()->with('success', 'Data guru berhasil dihapus.');
    }
}

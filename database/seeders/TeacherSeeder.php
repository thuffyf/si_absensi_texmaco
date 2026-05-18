<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = [
            [
                'nip' => '196812251992031003',
                'name' => 'Budi Santoso',
                'email' => 'budi@texmaco.id',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'subject' => 'Matematika',
                'phone' => '0812-1111-1111',
                'date_of_birth' => '1968-12-25',
                'status' => 'aktif',
            ],
            [
                'nip' => '197503141998032001',
                'name' => 'Siti Nurhaliza',
                'email' => 'siti@texmaco.id',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'subject' => 'Fisika',
                'phone' => '0812-2222-2222',
                'date_of_birth' => '1975-03-14',
                'status' => 'aktif',
            ],
            [
                'nip' => '197001151995031001',
                'name' => 'Hendra Gunawan',
                'email' => 'hendra@texmaco.id',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'subject' => 'Sejarah',
                'phone' => '0812-3333-3333',
                'date_of_birth' => '1970-01-15',
                'status' => 'cuti',
            ],
            [
                'nip' => '197806231999031001',
                'name' => 'Ani Wijaya',
                'email' => 'ani@texmaco.id',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'subject' => 'Kimia',
                'phone' => '0812-4444-4444',
                'date_of_birth' => '1978-06-23',
                'status' => 'aktif',
            ],
            [
                'nip' => '198203241998032001',
                'name' => 'Citra Kusuma',
                'email' => 'citra@texmaco.id',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'subject' => 'Biologi',
                'phone' => '0812-5555-5555',
                'date_of_birth' => '1982-03-24',
                'status' => 'aktif',
            ],
            [
                'nip' => '999999999999999999',
                'name' => 'Guru SITEXA',
                'email' => 'guru@gmail.com',
                'password' => Hash::make('guru123'),
                'role' => 'guru',
                'subject' => 'Bimbingan',
                'phone' => '0812-9000-0000',
                'date_of_birth' => '1980-01-01',
                'status' => 'aktif',
            ],
        ];

        foreach ($teachers as $teacher) {
            Teacher::updateOrCreate(
                ['nip' => $teacher['nip']],
                $teacher
            );
        }
    }
}

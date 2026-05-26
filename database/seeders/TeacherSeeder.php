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
                'name' => 'Bakti Prasetyo',
                'email' => 'bakti@texmaco.id',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'subject' => 'Produktif TEI',
                'phone' => '0812-1111-1111',
                'date_of_birth' => '1968-12-25',
                'status' => 'aktif',
            ],
            [
                'nip' => '197503141998032001',
                'name' => 'Najib Ramadhan',
                'email' => 'najib@texmaco.id',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'subject' => 'Produktif TEI',
                'phone' => '0812-2222-2222',
                'date_of_birth' => '1975-03-14',
                'status' => 'aktif',
            ],
            [
                'nip' => '197001151995031001',
                'name' => 'Dwi Lestari',
                'email' => 'dwi@texmaco.id',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'subject' => 'Bimbingan Konseling',
                'phone' => '0812-3333-3333',
                'date_of_birth' => '1970-01-15',
                'status' => 'aktif',
            ],
            [
                'nip' => '197806231999031001',
                'name' => 'Susi Handayani',
                'email' => 'susi@texmaco.id',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'subject' => 'Produktif TEI',
                'phone' => '0812-4444-4444',
                'date_of_birth' => '1978-06-23',
                'status' => 'aktif',
            ],
            [
                'nip' => '198203241998032001',
                'name' => 'Vinni Marlina',
                'email' => 'vinni@texmaco.id',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'subject' => 'Bimbingan Konseling',
                'phone' => '0812-5555-5555',
                'date_of_birth' => '1982-03-24',
                'status' => 'aktif',
            ],
            [
                'nip' => '198504121999032006',
                'name' => 'Rina Kartika',
                'email' => 'rina@texmaco.id',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'subject' => 'Matematika',
                'phone' => '0812-6666-6666',
                'date_of_birth' => '1985-04-12',
                'status' => 'aktif',
            ],
            [
                'nip' => '198711302006041004',
                'name' => 'Agus Setiawan',
                'email' => 'agus@texmaco.id',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'subject' => 'Bahasa Indonesia',
                'phone' => '0812-7777-7777',
                'date_of_birth' => '1987-11-30',
                'status' => 'aktif',
            ],
            [
                'nip' => '199002142010012003',
                'name' => 'Maya Puspita',
                'email' => 'maya@texmaco.id',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'subject' => 'Bahasa Inggris',
                'phone' => '0812-8888-8888',
                'date_of_birth' => '1990-02-14',
                'status' => 'aktif',
            ],
            [
                'nip' => '198209082005011002',
                'name' => 'Hendra Gunawan',
                'email' => 'hendra@texmaco.id',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'subject' => 'Sejarah Indonesia',
                'phone' => '0812-3333-9999',
                'date_of_birth' => '1982-09-08',
                'status' => 'cuti',
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

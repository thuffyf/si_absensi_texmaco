<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = [
            [
                'nim' => '12001',
                'name' => 'Rafa Prakasa',
                'username' => 'rafa',
                'email' => 'rafa.prakasa@student.com',
                'password' => Hash::make('password'),
                'date_of_birth' => '2007-05-14',
                'class_name' => 'XII IPA 1',
                'major' => 'IPA',
                'status' => 'aktif',
                'nfc_type' => 'kartu',
                'uid_kartu' => 'UID-12001',
                'phone' => '0812-0001-0001',
            ],
            [
                'nim' => '12002',
                'name' => 'Silvi Lestari',
                'username' => 'silvi',
                'email' => 'silvi.lestari@student.com',
                'password' => Hash::make('password'),
                'date_of_birth' => '2007-07-01',
                'class_name' => 'XII IPA 1',
                'major' => 'IPA',
                'status' => 'aktif',
                'nfc_type' => 'kartu',
                'uid_kartu' => 'UID-12002',
                'phone' => '0812-0002-0002',
            ],
            [
                'nim' => '12003',
                'name' => 'Adi Pratama',
                'username' => 'adi',
                'email' => 'adi.pratama@student.com',
                'password' => Hash::make('password'),
                'date_of_birth' => '2007-11-09',
                'class_name' => 'XII IPA 1',
                'major' => 'IPA',
                'status' => 'aktif',
                'nfc_type' => 'handphone',
                'phone' => '0812-0003-0003',
            ],
            [
                'nim' => '12004',
                'name' => 'Mira Putri',
                'username' => 'mira',
                'email' => 'mira.putri@student.com',
                'password' => Hash::make('password'),
                'date_of_birth' => '2007-02-20',
                'class_name' => 'XII IPA 2',
                'major' => 'IPA',
                'status' => 'aktif',
                'nfc_type' => 'kartu',
                'uid_kartu' => 'UID-12004',
                'phone' => '0812-0004-0004',
            ],
            [
                'nim' => '12005',
                'name' => 'Danu Wijaya',
                'username' => 'danu',
                'email' => 'danu.wijaya@student.com',
                'password' => Hash::make('password'),
                'date_of_birth' => '2007-03-18',
                'class_name' => 'XII IPA 2',
                'major' => 'IPA',
                'status' => 'aktif',
                'nfc_type' => 'belum_terdaftar',
                'phone' => '0812-0005-0005',
            ],
            [
                'nim' => '12006',
                'name' => 'Budi Santoso',
                'username' => 'budi',
                'email' => 'budi.santoso@student.com',
                'password' => Hash::make('password'),
                'date_of_birth' => '2007-08-10',
                'class_name' => 'XII IPS 1',
                'major' => 'IPS',
                'status' => 'aktif',
                'nfc_type' => 'kartu',
                'uid_kartu' => 'UID-12006',
                'phone' => '0812-0006-0006',
            ],
            [
                'nim' => '12007',
                'name' => 'Ani Wijaya',
                'username' => 'ani',
                'email' => 'ani.wijaya@student.com',
                'password' => Hash::make('password'),
                'date_of_birth' => '2007-10-05',
                'class_name' => 'XII IPS 1',
                'major' => 'IPS',
                'status' => 'aktif',
                'nfc_type' => 'handphone',
                'phone' => '0812-0007-0007',
            ],
            [
                'nim' => '12008',
                'name' => 'Citra Kusuma',
                'username' => 'citra',
                'email' => 'citra.kusuma@student.com',
                'password' => Hash::make('password'),
                'date_of_birth' => '2008-01-12',
                'class_name' => 'XI IPA 1',
                'major' => 'IPA',
                'status' => 'aktif',
                'nfc_type' => 'kartu',
                'uid_kartu' => 'UID-12008',
                'phone' => '0812-0008-0008',
            ],
        ];

        foreach ($students as $student) {
            Student::updateOrCreate(
                ['nim' => $student['nim']],
                $student
            );
        }
    }
}

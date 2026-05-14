<?php

namespace Database\Seeders;

use App\Models\Homeroom;
use Illuminate\Database\Seeder;

class HomeroomSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['class_name' => 'X TEI', 'homeroom_teacher_name' => 'Citra Kusuma, S.Pd.'],
            ['class_name' => 'XI TEI', 'homeroom_teacher_name' => 'Ani Wijaya, S.Pd.'],
            ['class_name' => 'XII TEI', 'homeroom_teacher_name' => 'Budi Santoso, S.Pd.'],
        ];

        foreach ($rows as $row) {
            Homeroom::updateOrCreate(
                ['class_name' => $row['class_name']],
                ['homeroom_teacher_name' => $row['homeroom_teacher_name']]
            );
        }
    }
}

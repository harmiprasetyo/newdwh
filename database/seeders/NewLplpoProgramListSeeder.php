<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewLplpoProgramListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programs = [
            'PROGRAM KUSTA',
            'PROGRAM FILARIASIS',
            'PROGRAM HIV',
            'JIWA',
            'PTM',
            'PROGRAM TB',
            'BMHP PKG',
            'PROGRAM AUSREM',
            'PROGRAM GIZI',
            'PROGRAM KESEHATAN IBU',
            'PROGRAM KESEHATAN ANAK',
            'PROGRAM MALARIA',
            'PROGRAM DIARE',
            'PROGRAM ZOONOSIS',
            'PROGRAM RABIES',
            'PROGRAM HEPATITIS B',
            'PROGRAM IMUNISASI',
            'LABORATORIUM',
            'KESLING',
        ];

        foreach ($programs as $program) {
            DB::table('new_lplpo_program_list')->updateOrInsert(
                [
                    'program_name' => $program,
                ],
                [
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ListTypeFaskesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            [
                'id' => 1,
                'typeFaskes' => 'Puskesmas'

            ],
            [
                'id' => 2,
                'typeFaskes' => 'Rumah Sakit'

            ],
            [
                'id' => 3,
                'typeFaskes' => 'Klinik'

            ],
            [
                'id' => 20,
                'typeFaskes' => 'TPMB'

            ],
        ];

        foreach ($data as $row) {
            DB::table('list_typefaskes')->updateOrInsert(
                ['id' => $row['id']],
                [
                    'typeFaskes' => $row['typeFaskes']
                ]
            );
        }
    }
}

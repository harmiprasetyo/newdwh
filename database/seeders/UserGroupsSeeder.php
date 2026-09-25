<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

Class UserGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            [
                'group_id' => 1,
                'group_name' => 'Administrator'
            ],
            [
                'group_id' => 2,
                'group_name' => 'Dinas Kesehatan'
            ],
            [
                'group_id' => 3,
                'group_name' => 'Admin Faskes'
            ],
            [
                'group_id' => 4,
                'group_name' => 'Program Puskesmas'
            ],
            [
                'group_id' => 5,
                'group_name' => 'Program Rumah Sakit'
            ],
        ];

        foreach ($data as $row) {
            DB::table('usergroups')->updateOrInsert(
                ['group_id' => $row['group_id']],
                [
                    'group_name' => $row['group_name']
                ]
            );
        }
    }
}

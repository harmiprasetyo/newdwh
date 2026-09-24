<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

Class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            [
                'role_id' => 1,
                'role_name' => 'Super Admin'
            ],
            [
                'role_id' => 2,
                'role_name' => 'Admin Faskes'
            ],
            [
                'role_id' => 3,
                'role_name' => 'Program Puskesmas'
            ],
            [
                'role_id' => 4,
                'role_name' => 'Program Rumah Sakit'
            ],
        ];

        foreach ($data as $row) {
            DB::table('user_roles')->updateOrInsert(
                ['role_id' => $row['role_id']],
                [
                    'role_name' => $row['role_name']
                ]
            );
        }
    }
}

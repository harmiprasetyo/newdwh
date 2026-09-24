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
                'id' => 1,
                'role_name' => 'Super Admin'
            ],
            [
                'id' => 2,
                'role_name' => 'Admin Faskes'
            ],
            [
                'id' => 3,
                'role_name' => 'Program Puskesmas'
            ],
            [
                'id' => 4,
                'role_name' => 'Program Rumah Sakit'
            ],
        ];

        foreach ($data as $row) {
            DB::table('user_roles')->updateOrInsert(
                ['id' => $row['id']],
                [
                    'role_name' => $row['role_name']
                ]
            );
        }
    }
}

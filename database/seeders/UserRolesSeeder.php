<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'role_name' => 'Super Admin',
                'groupId' => 1,
                'created_at' => '2026-06-06 02:57:26',
                'updated_at' => '2026-06-06 02:57:26',
            ],
            [
                'id' => 2,
                'role_name' => 'Admin',
                'groupId' => 2,
                'created_at' => '2026-06-06 02:57:26',
                'updated_at' => '2026-08-16 16:40:16',
            ],
            [
                'id' => 3,
                'role_name' => 'Farmasi',
                'groupId' => 2,
                'created_at' => '2026-06-06 02:57:26',
                'updated_at' => '2026-06-06 02:57:26',
            ],
            [
                'id' => 4,
                'role_name' => 'Program',
                'groupId' => 3,
                'created_at' => '2026-06-06 02:57:26',
                'updated_at' => '2026-06-06 02:57:26',
            ],
            [
                'id' => 5,
                'role_name' => 'Farmasi',
                'groupId' => 3,
                'created_at' => '2026-06-06 02:57:26',
                'updated_at' => '2026-06-06 02:57:26',
            ],
            [
                'id' => 6,
                'role_name' => 'Dokter',
                'groupId' => 4,
                'created_at' => '2026-06-06 02:57:26',
                'updated_at' => '2026-06-06 02:57:26',
            ],
            [
                'id' => 7,
                'role_name' => 'Bidan',
                'groupId' => 4,
                'created_at' => '2026-06-06 02:57:26',
                'updated_at' => '2026-06-06 02:57:26',
            ],
        ];

        foreach ($data as $row) {
            DB::table('user_roles')->updateOrInsert(
                ['id' => $row['id']],
                [
                    'role_name' => $row['role_name'],
                    'groupId' => $row['groupId'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at'],
                ]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\UserGroups;
use App\Models\UsersApp;
use App\Models\Master\ListTypeFaskes;
use App\Models\Master\MasterFaskes;
use App\Models\Master\LabelLplpo;
use App\Models\Api\Tenant;
use App\Models\Api\ApiKey;
use App\Models\UserRoles;
class DatabaseSeeder extends Seeder
{
    public function run()
    {
         $this->call([
            ListTypeFaskesSeeder::class,
        ]);

    $this->call([
            NewLplpoKategoriSeeder::class,
        ]);

          $this->call([
            MasterFaskesSeeder::class,
        ]);

         $this->call([
    MasterObatSeeder::class,
]);

 $this->call([
    MasterObatBHPSeeder::class,
]);

    /**
         * =========================
         * USER GROUPS
         * =========================
         */
        /*
        UserGroups::upsert([

            [
            "group_id"=>"1",
            "group_name"=>"Administrator"
                ],
            [
            "group_id"=>"2",
            "group_name"=>"Dinas Kesehatan"],
            [
            "group_id"=>"3",
            "group_name"=>"Admin Faskes"],
            ["group_id"=>"4",
                "group_name"=>"Program Puskesmas"],
                ["group_id"=>"5",
                "group_name"=>"Farmasi Puskesmas"],
                ["group_id"=>"6",
                "group_name"=>"TPMB"],
        ], ['group_id'], ['group_name']);
*/


/*
   UserRoles::upsert([[
    'id' => 1,
        'role_name' => 'Super Admin',
        'groupId' => 1
    ],
    [
        'id' => 2,
        'role_name' => 'program',
        'groupId' => 2
    ],
     [
        'id' => 3,
        'role_name' => 'farmasi',
        'groupId' => 2
    ],
    [
        'id' => 4,
        'role_name' => 'program',
        'groupId' => 3
    ],
    [
        'id' => 5,
        'role_name' => 'farmasi',
        'groupId' => 3
    ],
    [
        'id' => 6,
        'role_name' => 'Dokter',
        'groupId' => 4
    ],
    [
        'id' => 7,
        'role_name' => 'Perawat',
        'groupId' => 4
    ],
    [
        'id' => 8,
        'role_name' => 'Bidan',
        'groupId' => 4
     ],
        [
            'id' => 9,
            'role_name' => 'Tenaga Kesehatan Lain',
            'groupId' => 4
        ]
    ],['id'],['role_name','groupId']);

*/

    UsersApp::updateOrCreate(
    ['username' => 'admin'],
    [
        'email' => 'admin@dinkes.go.id',
        'namalengkap' => 'Administrator',
        'groupid' => 1,
        'role_id' => UserRoles::where('role_name', 'Super Admin')
            ->where('groupId', 1)
            ->value('id'),
        'kodeFaskes' => null,
        'namaFaskes' => null,
        'kodePropinsi' => null,
        'kodeKota' => null,
        'kodeKecamatan' => null,
        'password' => Hash::make('123456'),
    ]
);

 $this->call([
            UserRolesSeeder::class,
        ]);
 $this->call([
            UserGroupsSeeder::class,
        ]);
$this->call([
            UsersAppSeeder::class,
        ]);
        /**
         * =========================
         * TYPE FASKES
         * =========================
         */
        ListTypeFaskes::upsert([
            ["id" => 1, "typeFaskes" => "Puskesmas"],
            ["id" => 2, "typeFaskes" => "Rumah Sakit"],
            ["id" => 3, "typeFaskes" => "Klinik"]
        ], ['id'], ['typeFaskes']);










    }
}

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
            \Laravolt\Indonesia\Seeds\DatabaseSeeder::class,
        ]);

    $this->call([
            ListTypeFaskesSeeder::class,
        ]);



          $this->call([
            MasterFaskesSeeder::class,
        ]);

          $this->call([
            NewLplpoProgramListSeeder::class,
        ]);

            $this->call([
            NewLplpoKategoriSeeder::class,
        ]);
         $this->call([
    MasterObatBHPSeeder::class,
]);

         $this->call([
            MasterObatSeeder::class,
            ]);




 $this->call([
            UserRolesSeeder::class,
        ]);
 $this->call([
            UserGroupsSeeder::class,
        ]);

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
            UsersAppSeeder::class,
        ]);











    }
}

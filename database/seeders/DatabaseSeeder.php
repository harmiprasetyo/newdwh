<?php

namespace Database\Seeders;

use App\Models\UserGroups;
use Illuminate\Database\Seeder;
use App\Models\users\authUser;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
              UserGroups::create(
            ["group_name"=>"Administrator"],
            ["group_name"=>"Dinas Kesehatan"],
            ["group_name"=>"Puskesmas/Rumah Sakit"],
            ["group_name"=>"Dokter/Tenaga Kesehatan"]
            );

        authUser::create([
           "email"=>"admin@dwh.org",
            "userName"=>"admin",
            "userGroupId"=>"1",
            "userFullName"=>"Administrator",
            "password"=>Hash::make("54321")

        ],
        [
           "email"=>"dokter@dwh.org",
            "userName"=>"dokter",
            "userGroupId"=>"4",
            "userFullName"=>"dr. Ahmad Yani",
            "password"=>Hash::make("54321")

        ]
        );


    }
}

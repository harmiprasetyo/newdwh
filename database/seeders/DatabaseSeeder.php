<?php

namespace Database\Seeders;

use App\Models\UserGroups;
use Illuminate\Database\Seeder;
use App\Models\users\authUser;
use Illuminate\Support\Facades\Hash;
use App\Models\UsersApp;
use Illuminate\Support\Str;


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
           UserGroups::insert([
    ["group_name"=>"Administrator"],
    ["group_name"=>"Dinas Kesehatan"],
    ["group_name"=>"Puskesmas/Rumah Sakit"],
    ["group_name"=>"Dokter/Tenaga Kesehatan"]
]);

      UsersApp::insert([
    [
    "userid"=>Str::uuid(),
    "email"=>"admin@dwh.org",
        "username"=>"admin",
        "groupid"=>"1",
        "namalengkap"=>"Administrator",
        "password"=>Hash::make("123456")
    ],
    [
     "userid"=>Str::uuid(),
    "email"=>"dokter@dwh.org",
        "username"=>"dokter",
        "groupid"=>"4",
        "namalengkap"=>"dr. Ahmad Yani",
        "password"=>Hash::make("123456")
    ]
]);




    }
}

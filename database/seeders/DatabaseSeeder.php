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

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        /**
         * =========================
         * USER GROUPS
         * =========================
         */
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
            "group_name"=>"Puskesmas/Rumah Sakit"],
            ["group_is"=>"4",
                "group_name"=>"Dokter/Tenaga Kesehatan"]
        ], ['group_id'], ['group_name']);


        /**
         * =========================
         * TYPE FASKES
         * =========================
         */
        ListTypeFaskes::upsert([
            ["typeFaskes"=>"Puskesmas"],
            ["typeFaskes"=>"Rumah Sakit"],
            ["typeFaskes"=>"Klinik"]
        ], ['typeFaskes'], []);



         LabelLplpo::upsert([
            ["kodeKab"=>"3201",
            "field1"=>"PKD",
            "field2"=>"Program",
            "field3"=>"JKN"]
        ], ['kodeKab'], ["field1","field2","field3"]);


        /**
         * =========================
         * MASTER FASKES
         * =========================
         */
        MasterFaskes::upsert([
            [
                "kodeFaskes"=>"P3201090203",
                "typeFaskes"=>"1",
                "kodePropinsi"=>"32",
                "kodeKabupaten"=>"3201",
                "kodeKecamatan"=>"320109",
                "kepemilikan"=>"Pemerintah",
                "namaFaskes"=>"Puskesmas Ciderum"
            ],
            [
                "kodeFaskes"=>"P3201090202",
                "typeFaskes"=>"1",
                "kodePropinsi"=>"32",
                "kodeKabupaten"=>"3201",
                "kodeKecamatan"=>"320109",
                "kepemilikan"=>"Pemerintah",
                "namaFaskes"=>"Puskesmas Cinagara"
            ],
            [
                "kodeFaskes"=>"P3201090201",
                "typeFaskes"=>"1",
                "kodePropinsi"=>"32",
                "kodeKabupaten"=>"3201",
                "kodeKecamatan"=>"320109",
                "kepemilikan"=>"Pemerintah",
                "namaFaskes"=>"Puskesmas Caringin Bogor"
            ],
              [
                "kodeFaskes"=>"P3201081101",
                "typeFaskes"=>"1",
                "kodePropinsi"=>"32",
                "kodeKabupaten"=>"3201",
                "kodeKecamatan"=>"320108",
                "kepemilikan"=>"Pemerintah",
                "namaFaskes"=>"Puskesmas Cigombong"
            ],
             [
                "kodeFaskes"=>"P3201080203",
                "typeFaskes"=>"1",
                "kodePropinsi"=>"32",
                "kodeKabupaten"=>"3201",
                "kodeKecamatan"=>"320108",
                "kepemilikan"=>"Pemerintah",
                "namaFaskes"=>"Puskesmas Sukaharja"
            ],
            [
                "kodeFaskes"=>"P3201071203",
                "typeFaskes"=>"1",
                "kodePropinsi"=>"32",
                "kodeKabupaten"=>"3201",
                "kodeKecamatan"=>"320107",
                "kepemilikan"=>"Pemerintah",
                "namaFaskes"=>"Puskesmas Sukaresmi Bogor"
            ],
            [
                "kodeFaskes"=>"P3201071202",
                "typeFaskes"=>"1",
                "kodePropinsi"=>"32",
                "kodeKabupaten"=>"3201",
                "kodeKecamatan"=>"320107",
                "kepemilikan"=>"Pemerintah",
                "namaFaskes"=>"Puskesmas Sirna Galih"
            ]

        ], ['kodeFaskes'], [
            'namaFaskes','typeFaskes','kodePropinsi','kodeKabupaten','kodeKecamatan','kepemilikan'
        ]);


        /**
         * =========================
         * USERS
         * =========================
         */
       UsersApp::upsert([
    [
        "userid"=>Str::uuid(),
        "email"=>"admin@dwh.org",
        "username"=>"admin",
        "groupid"=>"1",
        "namalengkap"=>"Administrator",
        "kodeFaskes"=>null,
        "namaFaskes"=>null,
        "password"=>Hash::make("123456")
    ],
    [
        "userid"=>Str::uuid(),
        "email"=>"dokter@dwh.org",
        "username"=>"dokter",
        "groupid"=>"4",
        "namalengkap"=>"dr. Ahmad Yani",
        "kodeFaskes"=>"P3201090203",
        "namaFaskes"=>null,
        "password"=>Hash::make("123456")
    ],
    [
        "userid"=>Str::uuid(),
        "email"=>"ciderum@dwh.org",
        "username"=>"ciderum",
        "groupid"=>"3",
        "namalengkap"=>"Puskesmas Ciderum",
        "kodeFaskes"=>"P3201090203",
        "namaFaskes"=>"Puskesmas Ciderum",
        "password"=>Hash::make("123456")
    ],
], ['email'], [
    'username','groupid','namalengkap','kodeFaskes','namaFaskes','password'
]);
    }
}

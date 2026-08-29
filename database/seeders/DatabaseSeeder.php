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
            "group_name"=>"Admin Faskes"],
            ["group_id"=>"4",
                "group_name"=>"Program Puskesmas"],
                ["group_id"=>"5",
                "group_name"=>"Farmasi Puskesmas"],
                ["group_id"=>"6",
                "group_name"=>"TPMB"],
        ], ['group_id'], ['group_name']);




   UserRoles::upsert([[
        'role_name' => 'Super Admin',
        'groupId' => 1
    ],
    [
        'role_name' => 'program',
        'groupId' => 2
    ],
     [
        'role_name' => 'farmasi',
        'groupId' => 2
    ],
    [
        'role_name' => 'program',
        'groupId' => 3
    ],
    [
        'role_name' => 'farmasi',
        'groupId' => 3
    ],
    [
        'role_name' => 'Dokter',
        'groupId' => 4
    ],
    [
        'role_name' => 'Perawat',
        'groupId' => 4
    ],
    [
        'role_name' => 'Bidan',
        'groupId' => 4
     ],
        [
            'role_name' => 'Tenaga Kesehatan Lain',
            'groupId' => 4
        ]
    ],['role_name','groupId'],['role_name','groupId']);




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




    }
}

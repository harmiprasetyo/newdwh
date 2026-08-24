<?php

namespace App\Services\AdminPanel;

use App\Models\MasterPosyandu;
use App\Models\AdminPanel\WilayahKerja\WilayahKerjaPosyandu;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogService;

class WilayahKerjaPosyanduService
{
    /*
    |--------------------------------------------------------------------------
    | USER GROUP
    |--------------------------------------------------------------------------
    */

    public function isGroup3($user): bool
    {
        return (int) ($user->groupid ?? 0) === 3;
    }

    public function isGroup12($user): bool
    {
        return in_array(
            (int) ($user->groupid ?? 0),
            [1, 2],
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BASE QUERY DATATABLE
    |--------------------------------------------------------------------------
    */

    public function getDatatableQuery($user)
    {
        $query = WilayahKerjaPosyandu::query()

            ->join(
                'master_posyandu',
                'master_wilayah_kerja_posyandu.kodePosyandu',
                '=',
                'master_posyandu.kodePosyandu'
            )

            ->leftJoin(
                'indonesia_villages',
                'master_posyandu.village_code',
                '=',
                'indonesia_villages.code'
            )

            ->leftJoin(
                'indonesia_districts',
                'master_posyandu.district_code',
                '=',
                'indonesia_districts.code'
            )

            ->leftJoin(
                'indonesia_cities',
                'master_posyandu.city_code',
                '=',
                'indonesia_cities.code'
            )

            ->leftJoin(
                'indonesia_provinces',
                'master_posyandu.province_code',
                '=',
                'indonesia_provinces.code'
            )

            ->select([
                'master_wilayah_kerja_posyandu.*',

                'master_posyandu.village_code as posyandu_village_code',

                'master_posyandu.kodeFaskes',

                'master_posyandu.namaPosyandu',

                'indonesia_villages.name as village_name',

                'indonesia_districts.name as district_name',

                'indonesia_cities.name as city_name',

                'indonesia_provinces.name as province_name',
            ]);


        /*
        |--------------------------------------------------------------------------
        | GROUP 3
        |--------------------------------------------------------------------------
        */

        if ($this->isGroup3($user)) {

            $query->where(
                'master_posyandu.kodeFaskes',
                $user->kodeFaskes
            );
        }


        /*
        |--------------------------------------------------------------------------
        | GROUP 1 & 2
        |--------------------------------------------------------------------------
        */

        if ($this->isGroup12($user)) {

            $query->where(
                'master_posyandu.city_code',
                $user->kodeKota
            );
        }


        return $query;
    }


    /*
    |--------------------------------------------------------------------------
    | POSYANDU
    |--------------------------------------------------------------------------
    */

    public function getPosyandu($user, ?string $search = null)
    {
        $query = MasterPosyandu::query();


        if ($this->isGroup3($user)) {

            $query->where(
                'kodeFaskes',
                $user->kodeFaskes
            );
        }


        if ($this->isGroup12($user)) {

            $query->where(
                'city_code',
                $user->kodeKota
            );
        }


        if ($search) {

            $query->where(function ($q) use ($search) {

                $q->where(
                    'kodePosyandu',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'namaPosyandu',
                    'like',
                    '%' . $search . '%'
                );

            });
        }


        return $query
            ->orderBy('namaPosyandu')
            ->limit(30)
            ->get([
                'kodePosyandu',
                'namaPosyandu',
                'village_code',
                'kodeFaskes',
                'city_code',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | FIND
    |--------------------------------------------------------------------------
    */

    public function find($id, $user)
    {
        $query = WilayahKerjaPosyandu::query()

            ->join(
                'master_posyandu',
                'master_wilayah_kerja_posyandu.kodePosyandu',
                '=',
                'master_posyandu.kodePosyandu'
            )

            ->where(
                'master_wilayah_kerja_posyandu.id',
                $id
            );


        if ($this->isGroup3($user)) {

            $query->where(
                'master_posyandu.kodeFaskes',
                $user->kodeFaskes
            );
        }


        if ($this->isGroup12($user)) {

            $query->where(
                'master_posyandu.city_code',
                $user->kodeKota
            );
        }


        return $query
            ->select([
                'master_wilayah_kerja_posyandu.*',
                'master_posyandu.village_code',
                'master_posyandu.kodeFaskes',
                'master_posyandu.namaPosyandu',
            ])
            ->firstOrFail();
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(array $data, $user)
    {
        /*
        |--------------------------------------------------------------------------
        | Pastikan Posyandu boleh digunakan user
        |--------------------------------------------------------------------------
        */

        $posyandu = $this->findAllowedPosyandu(
            $data['kodePosyandu'],
            $user
        );


        /*
        |--------------------------------------------------------------------------
        | Normalisasi RW
        |--------------------------------------------------------------------------
        */

        $rw = collect(
            explode(',', $data['rw'] ?? '')
        )
            ->map(function ($item) {

                return str_pad(
                    trim($item),
                    2,
                    '0',
                    STR_PAD_LEFT
                );

            })
            ->filter()
            ->unique()
            ->sort()
            ->implode(',');


        /*
        |--------------------------------------------------------------------------
        | TRANSACTION
        |--------------------------------------------------------------------------
        */

        return DB::transaction(function () use (
            $posyandu,
            $rw
        ) {

            /*
            |--------------------------------------------------------------------------
            | village_code WAJIB dari master_posyandu
            |--------------------------------------------------------------------------
            */

            $wilayah = WilayahKerjaPosyandu::create([

                'kodePosyandu' =>
                    $posyandu->kodePosyandu,

                'village_code' =>
                    $posyandu->village_code,

                'rw' =>
                    $rw,

            ]);


            /*
            |--------------------------------------------------------------------------
            | ACTIVITY LOG - CREATE
            |--------------------------------------------------------------------------
            */

            ActivityLogService::log(

                action: 'create',

                module: 'Wilayah Kerja Posyandu',

                description:
                    'Menambahkan wilayah kerja Posyandu '
                    . $posyandu->namaPosyandu,

                subject: $wilayah,

                newValues:
                    $wilayah->toArray()

            );


            return $wilayah;
        });
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        $id,
        array $data,
        $user
    ) {

        /*
        |--------------------------------------------------------------------------
        | Ambil data yang boleh diakses user
        |--------------------------------------------------------------------------
        */

        $wilayah = $this->find(
            $id,
            $user
        );


        /*
        |--------------------------------------------------------------------------
        | Posyandu baru harus tetap berada dalam wilayah user
        |--------------------------------------------------------------------------
        */

        $posyandu = $this->findAllowedPosyandu(
            $data['kodePosyandu'],
            $user
        );


        /*
        |--------------------------------------------------------------------------
        | Simpan kondisi lama
        |--------------------------------------------------------------------------
        */

        $oldValues =
            $wilayah->getOriginal();


        /*
        |--------------------------------------------------------------------------
        | Normalisasi RW
        |--------------------------------------------------------------------------
        */

        $rw = collect(
            explode(',', $data['rw'] ?? '')
        )
            ->map(function ($item) {

                return str_pad(
                    trim($item),
                    2,
                    '0',
                    STR_PAD_LEFT
                );

            })
            ->filter()
            ->unique()
            ->sort()
            ->implode(',');


        /*
        |--------------------------------------------------------------------------
        | TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $wilayah,
            $posyandu,
            $rw,
            $oldValues
        ) {

            $wilayah->update([

                'kodePosyandu' =>
                    $posyandu->kodePosyandu,

                /*
                |--------------------------------------------------------------------------
                | village_code selalu mengikuti master_posyandu
                |--------------------------------------------------------------------------
                */

                'village_code' =>
                    $posyandu->village_code,

                'rw' =>
                    $rw,

            ]);


            /*
            |--------------------------------------------------------------------------
            | ACTIVITY LOG - UPDATE
            |--------------------------------------------------------------------------
            */

            ActivityLogService::log(

                action: 'update',

                module: 'Wilayah Kerja Posyandu',

                description:
                    'Mengubah wilayah kerja Posyandu '
                    . $posyandu->namaPosyandu,

                subject: $wilayah,

                oldValues:
                    $oldValues,

                newValues:
                    $wilayah->fresh()->toArray()

            );
        });


        return $wilayah->fresh();
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete($id, $user)
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil data yang boleh dihapus user
        |--------------------------------------------------------------------------
        */

        $wilayah = $this->find(
            $id,
            $user
        );


        /*
        |--------------------------------------------------------------------------
        | Simpan data lama
        |--------------------------------------------------------------------------
        */

        $oldValues =
            $wilayah->toArray();


        /*
        |--------------------------------------------------------------------------
        | Simpan subject ID sebelum delete
        |--------------------------------------------------------------------------
        */

        $subjectId =
            $wilayah->getKey();


        /*
        |--------------------------------------------------------------------------
        | TRANSACTION
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $wilayah,
            $oldValues,
            $subjectId
        ) {

            /*
            |--------------------------------------------------------------------------
            | DELETE
            |--------------------------------------------------------------------------
            */

            $wilayah->delete();


            /*
            |--------------------------------------------------------------------------
            | ACTIVITY LOG - DELETE
            |--------------------------------------------------------------------------
            */

            ActivityLogService::log(

                action: 'delete',

                module: 'Wilayah Kerja Posyandu',

                description:
                    'Menghapus wilayah kerja Posyandu',

                subject: $wilayah,

                oldValues:
                    array_merge(
                        $oldValues,
                        [
                            'deleted_subject_id' =>
                                $subjectId
                        ]
                    )

            );
        });


        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | FIND ALLOWED POSYANDU
    |--------------------------------------------------------------------------
    */

    protected function findAllowedPosyandu(
        string $kodePosyandu,
        $user
    ) {

        $query = MasterPosyandu::query()

            ->where(
                'kodePosyandu',
                $kodePosyandu
            );


        /*
        |--------------------------------------------------------------------------
        | GROUP 3
        |--------------------------------------------------------------------------
        */

        if ($this->isGroup3($user)) {

            $query->where(
                'kodeFaskes',
                $user->kodeFaskes
            );
        }


        /*
        |--------------------------------------------------------------------------
        | GROUP 1 & 2
        |--------------------------------------------------------------------------
        */

        if ($this->isGroup12($user)) {

            $query->where(
                'city_code',
                $user->kodeKota
            );
        }


        return $query->firstOrFail();
    }
}

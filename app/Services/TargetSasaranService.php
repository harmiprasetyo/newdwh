<?php

namespace App\Services;

use App\Models\TargetSasaran;
use App\Models\MasterPosyandu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogService;

class TargetSasaranService
{
    protected ActivityLogService $activityLog;

    public function __construct(
        ActivityLogService $activityLog
    ) {
        $this->activityLog = $activityLog;
    }


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
    | GET POSYANDU
    |--------------------------------------------------------------------------
    |
    | Posyandu yang boleh dipilih oleh user.
    |
    */

    public function getPosyandu($user, ?string $search = null)
    {
        $query = MasterPosyandu::query()
            ->where('isActive', 1);


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
        | SEARCH
        |--------------------------------------------------------------------------
        */

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
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | FIND POSYANDU YANG DIIZINKAN
    |--------------------------------------------------------------------------
    */

    public function findAllowedPosyandu(
        $posyanduId,
        $user
    ) {

        $query = MasterPosyandu::query()
            ->where('id', $posyanduId)
            ->where('isActive', 1);


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


        return $query->firstOrFail();
    }


    /*
    |--------------------------------------------------------------------------
    | DATATABLE QUERY
    |--------------------------------------------------------------------------
    */

    public function getData($user, array $filters = [])
    {
        $query = TargetSasaran::query()
            ->select(
                'target_sasaran.*'
            );


        /*
        |--------------------------------------------------------------------------
        | GROUP 3
        |--------------------------------------------------------------------------
        |
        | Hanya target Posyandu milik kodeFaskes user.
        |
        */

        if ($this->isGroup3($user)) {

            $query->whereHas(
                'posyandu',
                function ($q) use ($user) {

                    $q->where(
                        'kodeFaskes',
                        $user->kodeFaskes
                    );

                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER POSYANDU
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['posyandu'])) {

            /*
            | Pastikan Group 3 tidak bisa memasukkan
            | posyandu milik faskes lain.
            */

            if ($this->isGroup3($user)) {

                $query->whereHas(
                    'posyandu',
                    function ($q) use ($user, $filters) {

                        $q->where(
                            'id',
                            $filters['posyandu']
                        );

                        $q->where(
                            'kodeFaskes',
                            $user->kodeFaskes
                        );

                    }
                );

            } else {

                $query->where(
                    'posyandu_id',
                    $filters['posyandu']
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER BULAN
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['bulan'])) {

            $query->where(
                'bulan',
                $filters['bulan']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER TAHUN
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['tahun'])) {

            $query->where(
                'tahun',
                $filters['tahun']
            );
        }


        return $query;
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        array $data,
        $user
    ) {

        return DB::transaction(
            function () use ($data, $user) {

                /*
                |--------------------------------------------------------------------------
                | POSYANDU HARUS SESUAI HAK AKSES USER
                |--------------------------------------------------------------------------
                */

                $posyandu =
                    $this->findAllowedPosyandu(
                        $data['posyandu_id'],
                        $user
                    );


                /*
                |--------------------------------------------------------------------------
                | CREATE
                |--------------------------------------------------------------------------
                */

                $target = TargetSasaran::create([

                    /*
                    |--------------------------------------------------------------------------
                    | SNAPSHOT WILAYAH
                    |--------------------------------------------------------------------------
                    */

                    'province_code' =>
                        $posyandu->province_code,

                    'city_code' =>
                        $posyandu->city_code,

                    'district_code' =>
                        $posyandu->district_code,

                    'village_code' =>
                        $posyandu->village_code,


                    /*
                    |--------------------------------------------------------------------------
                    | SNAPSHOT POSYANDU
                    |--------------------------------------------------------------------------
                    */

                    'kodePosyandu' =>
                        $posyandu->kodePosyandu,

                    'namaPosyandu' =>
                        $posyandu->namaPosyandu,

                    'posyandu_id' =>
                        $posyandu->id,


                    /*
                    |--------------------------------------------------------------------------
                    | PERIODE
                    |--------------------------------------------------------------------------
                    */

                    'bulan' =>
                        $data['bulan'],

                    'tahun' =>
                        $data['tahun'],


                    /*
                    |--------------------------------------------------------------------------
                    | WILAYAH RT/RW
                    |--------------------------------------------------------------------------
                    */

                    'rw' =>
                        $data['rw'],

                    'rt' =>
                        $data['rt'],


                    /*
                    |--------------------------------------------------------------------------
                    | SASARAN
                    |--------------------------------------------------------------------------
                    */

                    'sasaran_ibu_hamil' =>
                        $data['sasaran_ibu_hamil'],

                    'sasaran_ibu_melahirkan' =>
                        $data['sasaran_ibu_melahirkan'],

                    'sasaran_bayi_baru_lahir' =>
                        $data['sasaran_bayi_baru_lahir'],


                    /*
                    |--------------------------------------------------------------------------
                    | USER
                    |--------------------------------------------------------------------------
                    */

                    'created_by' =>
                        Auth::id(),

                ]);


                /*
                |--------------------------------------------------------------------------
                | ACTIVITY LOG
                |--------------------------------------------------------------------------
                */

                $this->activityLog->log(

                    action: 'create',

                    module: 'target_sasaran',

                    description:
                        'Menambahkan target sasaran Posyandu.',

                    subject: $target,

                    oldValues: null,

                    newValues: $target->toArray()

                );


                return $target;
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        TargetSasaran $model,
        array $data,
        $user
    ) {

        return DB::transaction(
            function () use (
                $model,
                $data,
                $user
            ) {

                /*
                |--------------------------------------------------------------------------
                | CEK DATA LAMA
                |--------------------------------------------------------------------------
                */

                $this->ensureAllowedTarget(
                    $model,
                    $user
                );


                /*
                |--------------------------------------------------------------------------
                | OLD DATA
                |--------------------------------------------------------------------------
                */

                $oldData =
                    $model->toArray();


                /*
                |--------------------------------------------------------------------------
                | POSYANDU BARU
                |--------------------------------------------------------------------------
                */

                $posyandu =
                    $this->findAllowedPosyandu(
                        $data['posyandu_id'],
                        $user
                    );


                /*
                |--------------------------------------------------------------------------
                | UPDATE
                |--------------------------------------------------------------------------
                */

                $model->update([

                    /*
                    |--------------------------------------------------------------------------
                    | SNAPSHOT WILAYAH
                    |--------------------------------------------------------------------------
                    */

                    'province_code' =>
                        $posyandu->province_code,

                    'city_code' =>
                        $posyandu->city_code,

                    'district_code' =>
                        $posyandu->district_code,

                    'village_code' =>
                        $posyandu->village_code,


                    /*
                    |--------------------------------------------------------------------------
                    | SNAPSHOT POSYANDU
                    |--------------------------------------------------------------------------
                    */

                    'kodePosyandu' =>
                        $posyandu->kodePosyandu,

                    'namaPosyandu' =>
                        $posyandu->namaPosyandu,

                    'posyandu_id' =>
                        $posyandu->id,


                    /*
                    |--------------------------------------------------------------------------
                    | DATA
                    |--------------------------------------------------------------------------
                    */

                    'bulan' =>
                        $data['bulan'],

                    'tahun' =>
                        $data['tahun'],

                    'rw' =>
                        $data['rw'],

                    'rt' =>
                        $data['rt'],

                    'sasaran_ibu_hamil' =>
                        $data['sasaran_ibu_hamil'],

                    'sasaran_ibu_melahirkan' =>
                        $data['sasaran_ibu_melahirkan'],

                    'sasaran_bayi_baru_lahir' =>
                        $data['sasaran_bayi_baru_lahir'],

                    'updated_by' =>
                        Auth::id(),

                ]);


                $model->refresh();


                /*
                |--------------------------------------------------------------------------
                | ACTIVITY LOG
                |--------------------------------------------------------------------------
                */

                $this->activityLog->log(

                    action: 'update',

                    module: 'target_sasaran',

                    description:
                        'Memperbarui target sasaran Posyandu.',

                    subject: $model,

                    oldValues: $oldData,

                    newValues: $model->toArray()

                );


                return $model;
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete(
        TargetSasaran $model,
        $user
    ) {

        return DB::transaction(
            function () use ($model, $user) {

                /*
                |--------------------------------------------------------------------------
                | CEK HAK AKSES
                |--------------------------------------------------------------------------
                */

                $this->ensureAllowedTarget(
                    $model,
                    $user
                );


                /*
                |--------------------------------------------------------------------------
                | OLD DATA
                |--------------------------------------------------------------------------
                */

                $oldData =
                    $model->toArray();


                /*
                |--------------------------------------------------------------------------
                | DELETE
                |--------------------------------------------------------------------------
                */

                $deleted =
                    $model->delete();


                /*
                |--------------------------------------------------------------------------
                | ACTIVITY LOG
                |--------------------------------------------------------------------------
                */

                if ($deleted) {

                    $this->activityLog->log(

                        action: 'delete',

                        module: 'target_sasaran',

                        description:
                            'Menghapus target sasaran Posyandu.',

                        subject: $model,

                        oldValues: $oldData,

                        newValues: null

                    );
                }


                return $deleted;
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDASI TARGET SESUAI USER
    |--------------------------------------------------------------------------
    */

    protected function ensureAllowedTarget(
        TargetSasaran $target,
        $user
    ) {

        /*
        |--------------------------------------------------------------------------
        | GROUP 3
        |--------------------------------------------------------------------------
        */

        if ($this->isGroup3($user)) {

            $allowed =
                MasterPosyandu::where(
                    'id',
                    $target->posyandu_id
                )
                ->where(
                    'kodeFaskes',
                    $user->kodeFaskes
                )
                ->exists();


            if (!$allowed) {

                abort(
                    403,
                    'Anda tidak memiliki akses terhadap data target sasaran ini.'
                );
            }
        }
    }
}

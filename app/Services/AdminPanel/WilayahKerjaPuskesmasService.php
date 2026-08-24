<?php

namespace App\Services\AdminPanel;

use App\Models\Master\MasterFaskes;
use App\Models\WilayahKerja\WilayahKerjaPuskesmas;
use App\Services\ActivityLogService;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Laravolt\Indonesia\Models\Village as IndonesiaVillage;

class WilayahKerjaPuskesmasService
{
    protected ActivityLogService $activityLog;

    public function __construct(
        ActivityLogService $activityLog
    ) {
        $this->activityLog = $activityLog;
    }

    /*
    |--------------------------------------------------------------------------
    | GROUP
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
    | GET DATA
    |--------------------------------------------------------------------------
    */

    public function getData(array $filters = [], $user = null)
    {
        $query = WilayahKerjaPuskesmas::query()
            ->with([
                'faskes',
                'desa.district.city.province',
            ]);


        /*
        |--------------------------------------------------------------------------
        | GROUP 3
        |--------------------------------------------------------------------------
        |
        | Hanya menampilkan wilayah kerja Puskesmas milik user.
        |
        */

        if ($user && $this->isGroup3($user)) {

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

        if (
            $user &&
            $this->isGroup12($user) &&
            !empty($user->kodeKota)
        ) {

            $query->whereHas(
                'faskes',
                function ($q) use ($user) {

                    $q->where(
                        'kodeKabupaten',
                        $user->kodeKota
                    );

                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER FASKES
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['kodeFaskes'])) {

            $query->where(
                'kodeFaskes',
                $filters['kodeFaskes']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILTER DESA
        |--------------------------------------------------------------------------
        */

        if (!empty($filters['kodeDesa'])) {

            $query->where(
                'kodeDesa',
                $filters['kodeDesa']
            );
        }


        return $query;
    }


    /*
    |--------------------------------------------------------------------------
    | GET FASKES
    |--------------------------------------------------------------------------
    */

    public function getFaskes($user, ?string $search = null)
    {
        $query = MasterFaskes::query();


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

        if (
            $this->isGroup12($user) &&
            !empty($user->kodeKota)
        ) {

            $query->where(
                'kodeKabupaten',
                $user->kodeKota
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
                    'kodeFaskes',
                    'like',
                    '%' . $search . '%'
                );

                $q->orWhere(
                    'namaFaskes',
                    'like',
                    '%' . $search . '%'
                );

            });
        }


        return $query
            ->orderBy('namaFaskes')
            ->limit(30)
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | FIND FASKES
    |--------------------------------------------------------------------------
    */

    public function findAllowedFaskes(
        string $kodeFaskes,
        $user
    ) {

        $query = MasterFaskes::query()
            ->where(
                'kodeFaskes',
                $kodeFaskes
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

        if (
            $this->isGroup12($user) &&
            !empty($user->kodeKota)
        ) {

            $query->where(
                'kodeKabupaten',
                $user->kodeKota
            );
        }


        return $query->firstOrFail();
    }


    /*
    |--------------------------------------------------------------------------
    | GET DESA BY FASKES
    |--------------------------------------------------------------------------
    */

    public function getDesaByFaskes(
        string $kodeFaskes,
        $user
    ) {

        $faskes = $this->findAllowedFaskes(
            $kodeFaskes,
            $user
        );


        return IndonesiaVillage::query()
            ->where(
                'code',
                'like',
                $faskes->kodeKecamatan . '%'
            )
            ->orderBy('name')
            ->get([
                'code',
                'name',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(
        array $data,
        $user
    ): WilayahKerjaPuskesmas {

        return DB::transaction(function () use (
            $data,
            $user
        ) {

            /*
            |--------------------------------------------------------------------------
            | GROUP 3
            |--------------------------------------------------------------------------
            |
            | Jangan percaya kodeFaskes dari browser.
            |
            */

            if ($this->isGroup3($user)) {

                $data['kodeFaskes'] =
                    $user->kodeFaskes;
            }


            /*
            |--------------------------------------------------------------------------
            | Pastikan Faskes boleh digunakan
            |--------------------------------------------------------------------------
            */

            $faskes = $this->findAllowedFaskes(
                $data['kodeFaskes'],
                $user
            );


            /*
            |--------------------------------------------------------------------------
            | Pastikan desa memang berada di kecamatan Faskes
            |--------------------------------------------------------------------------
            */

            if (
                !str_starts_with(
                    $data['kodeDesa'],
                    $faskes->kodeKecamatan
                )
            ) {

                throw new \RuntimeException(
                    'Desa tidak sesuai dengan wilayah kerja Puskesmas.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | CREATE
            |--------------------------------------------------------------------------
            */

            $wilayah =
                WilayahKerjaPuskesmas::create([

                    'kodeFaskes' =>
                        $faskes->kodeFaskes,

                    'kodeDesa' =>
                        $data['kodeDesa'],

                ]);


            $wilayah->load([
                'faskes',
                'desa.district.city.province',
            ]);


            /*
            |--------------------------------------------------------------------------
            | ACTIVITY LOG
            |--------------------------------------------------------------------------
            */

            $this->activityLog->log(
                action: 'create',
                module: 'wilayah_kerja_puskesmas',
                description:
                    'Menambahkan wilayah kerja Puskesmas.',
                subject: $wilayah,
                oldValues: null,
                newValues: $wilayah->toArray()
            );


            Log::info(
                'Wilayah Kerja Puskesmas berhasil ditambahkan.',
                [
                    'id' =>
                        $wilayah->id,

                    'kodeFaskes' =>
                        $wilayah->kodeFaskes,

                    'kodeDesa' =>
                        $wilayah->kodeDesa,

                    'user_id' =>
                        auth()->id(),
                ]
            );


            return $wilayah;
        });
    }


    /*
    |--------------------------------------------------------------------------
    | FIND
    |--------------------------------------------------------------------------
    */

    public function find(
        $id,
        $user
    ): WilayahKerjaPuskesmas {

        $query =
            WilayahKerjaPuskesmas::query();


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

        if (
            $this->isGroup12($user) &&
            !empty($user->kodeKota)
        ) {

            $query->whereHas(
                'faskes',
                function ($q) use ($user) {

                    $q->where(
                        'kodeKabupaten',
                        $user->kodeKota
                    );

                }
            );
        }


        return $query
            ->with([
                'faskes',
                'desa.district.city.province',
            ])
            ->findOrFail($id);
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        WilayahKerjaPuskesmas $wilayah,
        array $data,
        $user
    ): WilayahKerjaPuskesmas {

        return DB::transaction(function () use (
            $wilayah,
            $data,
            $user
        ) {

            /*
            |--------------------------------------------------------------------------
            | Pastikan record masih boleh diakses
            |--------------------------------------------------------------------------
            */

            $wilayah =
                $this->find(
                    $wilayah->id,
                    $user
                );


            /*
            |--------------------------------------------------------------------------
            | GROUP 3
            |--------------------------------------------------------------------------
            */

            if ($this->isGroup3($user)) {

                $data['kodeFaskes'] =
                    $user->kodeFaskes;
            }


            /*
            |--------------------------------------------------------------------------
            | FASKES
            |--------------------------------------------------------------------------
            */

            $faskes =
                $this->findAllowedFaskes(
                    $data['kodeFaskes'],
                    $user
                );


            /*
            |--------------------------------------------------------------------------
            | DESA HARUS SESUAI KECAMATAN FASKES
            |--------------------------------------------------------------------------
            */

            if (
                !str_starts_with(
                    $data['kodeDesa'],
                    $faskes->kodeKecamatan
                )
            ) {

                throw new \RuntimeException(
                    'Desa tidak sesuai dengan wilayah kerja Puskesmas.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | OLD DATA
            |--------------------------------------------------------------------------
            */

            $oldData =
                $wilayah->toArray();


            /*
            |--------------------------------------------------------------------------
            | UPDATE
            |--------------------------------------------------------------------------
            */

            $wilayah->update([

                'kodeFaskes' =>
                    $faskes->kodeFaskes,

                'kodeDesa' =>
                    $data['kodeDesa'],

            ]);


            $wilayah->refresh();

            $wilayah->load([
                'faskes',
                'desa.district.city.province',
            ]);


            /*
            |--------------------------------------------------------------------------
            | NEW DATA
            |--------------------------------------------------------------------------
            */

            $newData =
                $wilayah->toArray();


            /*
            |--------------------------------------------------------------------------
            | ACTIVITY LOG
            |--------------------------------------------------------------------------
            */

            $this->activityLog->log(
                action: 'update',
                module: 'wilayah_kerja_puskesmas',
                description:
                    'Memperbarui wilayah kerja Puskesmas.',
                subject: $wilayah,
                oldValues: $oldData,
                newValues: $newData
            );


            Log::info(
                'Wilayah Kerja Puskesmas berhasil diperbarui.',
                [
                    'id' =>
                        $wilayah->id,

                    'old' =>
                        $oldData,

                    'new' =>
                        $newData,

                    'user_id' =>
                        auth()->id(),
                ]
            );


            return $wilayah;
        });
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete(
        WilayahKerjaPuskesmas $wilayah,
        $user
    ): bool {

        return DB::transaction(function () use (
            $wilayah,
            $user
        ) {

            /*
            |--------------------------------------------------------------------------
            | Pastikan user boleh menghapus record ini
            |--------------------------------------------------------------------------
            */

            $wilayah =
                $this->find(
                    $wilayah->id,
                    $user
                );


            /*
            |--------------------------------------------------------------------------
            | OLD DATA
            |--------------------------------------------------------------------------
            */

            $oldData =
                $wilayah->toArray();


            $id =
                $wilayah->id;


            /*
            |--------------------------------------------------------------------------
            | DELETE
            |--------------------------------------------------------------------------
            */

            try {

                $deleted =
                    $wilayah->delete();

            } catch (QueryException $e) {

                Log::error(
                    'Gagal DELETE Wilayah Kerja Puskesmas karena foreign key.',
                    [
                        'id' =>
                            $id,

                        'user_id' =>
                            auth()->id(),

                        'error' =>
                            $e->getMessage(),
                    ]
                );

                throw $e;
            }


            /*
            |--------------------------------------------------------------------------
            | ACTIVITY LOG
            |--------------------------------------------------------------------------
            */

            if ($deleted) {

                $this->activityLog->log(
                    action: 'delete',
                    module: 'wilayah_kerja_puskesmas',
                    description:
                        'Menghapus wilayah kerja Puskesmas.',
                    subject: $wilayah,
                    oldValues: $oldData,
                    newValues: null
                );


                Log::warning(
                    'Wilayah Kerja Puskesmas dihapus.',
                    [
                        'id' =>
                            $id,

                        'data' =>
                            $oldData,

                        'user_id' =>
                            auth()->id(),
                    ]
                );
            }


            return $deleted;
        });
    }
}

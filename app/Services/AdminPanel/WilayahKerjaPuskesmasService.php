<?php

namespace App\Services\AdminPanel;

use App\Models\WilayahKerja\WilayahKerjaPuskesmas;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

use App\Services\ActivityLogService;

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
    | GET DATA
    |--------------------------------------------------------------------------
    */

    public function getData(array $filters = [])
    {
        $query = WilayahKerjaPuskesmas::query()
            ->with([
                'faskes',
                'desa.district.city.province',
            ]);

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
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(array $data): WilayahKerjaPuskesmas
    {
        return DB::transaction(function () use ($data) {

            $wilayah = WilayahKerjaPuskesmas::create([
                'kodeFaskes' => $data['kodeFaskes'],
                'kodeDesa'   => $data['kodeDesa'],
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
                description: 'Menambahkan wilayah kerja Puskesmas.',
                subject: $wilayah,
                oldValues: null,
                newValues: $wilayah->toArray()
            );

            Log::info(
                'Wilayah Kerja Puskesmas berhasil ditambahkan.',
                [
                    'id' => $wilayah->id,
                    'kodeFaskes' => $wilayah->kodeFaskes,
                    'kodeDesa' => $wilayah->kodeDesa,
                    'user_id' => auth()->id(),
                ]
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
        WilayahKerjaPuskesmas $wilayah,
        array $data
    ): WilayahKerjaPuskesmas {

        return DB::transaction(function () use (
            $wilayah,
            $data
        ) {

            /*
            |--------------------------------------------------------------------------
            | OLD DATA
            |--------------------------------------------------------------------------
            */

            $oldData = $wilayah->toArray();

            /*
            |--------------------------------------------------------------------------
            | UPDATE
            |--------------------------------------------------------------------------
            */

            $wilayah->update([
                'kodeFaskes' => $data['kodeFaskes'],
                'kodeDesa'   => $data['kodeDesa'],
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

            $newData = $wilayah->toArray();

            /*
            |--------------------------------------------------------------------------
            | ACTIVITY LOG
            |--------------------------------------------------------------------------
            */

            $this->activityLog->log(
                action: 'update',
                module: 'wilayah_kerja_puskesmas',
                description: 'Memperbarui wilayah kerja Puskesmas.',
                subject: $wilayah,
                oldValues: $oldData,
                newValues: $newData
            );

            Log::info(
                'Wilayah Kerja Puskesmas berhasil diperbarui.',
                [
                    'id' => $wilayah->id,
                    'old' => $oldData,
                    'new' => $newData,
                    'user_id' => auth()->id(),
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
        WilayahKerjaPuskesmas $wilayah
    ): bool {

        return DB::transaction(function () use ($wilayah) {

            /*
            |--------------------------------------------------------------------------
            | Simpan data sebelum delete
            |--------------------------------------------------------------------------
            */

            $oldData = $wilayah->toArray();

            $id = $wilayah->id;

            /*
            |--------------------------------------------------------------------------
            | DELETE
            |--------------------------------------------------------------------------
            */

            try {

                $deleted = $wilayah->delete();

            } catch (QueryException $e) {

                /*
                |--------------------------------------------------------------------------
                | FOREIGN KEY
                |--------------------------------------------------------------------------
                */

                Log::error(
                    'Gagal DELETE Wilayah Kerja Puskesmas karena foreign key.',
                    [
                        'id' => $id,
                        'user_id' => auth()->id(),
                        'error' => $e->getMessage(),
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
                    description: 'Menghapus wilayah kerja Puskesmas.',
                    subject: $wilayah,
                    oldValues: $oldData,
                    newValues: null
                );

                Log::warning(
                    'Wilayah Kerja Puskesmas dihapus.',
                    [
                        'id' => $id,
                        'data' => $oldData,
                        'user_id' => auth()->id(),
                    ]
                );
            }

            return $deleted;
        });
    }
}

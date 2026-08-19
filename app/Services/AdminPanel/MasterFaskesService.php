<?php

namespace App\Services\AdminPanel;

use App\Models\Master\MasterFaskes;
use App\Models\MasterPosyandu;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MasterFaskesService
{
    /**
     * Query utama Master Faskes
     */
    public function query()
    {
        return MasterFaskes::query()
            ->with([
                'type',
                'provinsi',
                'kota',
                'kecamatan',
            ]);
    }


    /**
     * Ambil satu data
     */
    public function find($id)
    {
        return MasterFaskes::with([
            'type',
            'provinsi',
            'kota',
            'kecamatan',
        ])->findOrFail($id);
    }


    /**
     * Simpan data baru
     */
    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {

            /*
            |--------------------------------------------------------------------------
            | Validasi kode faskes
            |--------------------------------------------------------------------------
            */

            $this->validateKodeFaskes(
                $data['kodeFaskes']
            );


            /*
            |--------------------------------------------------------------------------
            | Create
            |--------------------------------------------------------------------------
            */

            $faskes = MasterFaskes::create([
                'kodeFaskes'    => $data['kodeFaskes'],
                'typeFaskes'    => $data['typeFaskes'],
                'kodePropinsi'  => $data['kodePropinsi'],
                'kodeKabupaten' => $data['kodeKabupaten'],
                'kodeKecamatan' => $data['kodeKecamatan'],
                'kepemilikan'   => $data['kepemilikan'] ?? null,
                'namaFaskes'    => $data['namaFaskes'],
            ]);


            /*
            |--------------------------------------------------------------------------
            | Activity Log - CREATE
            |--------------------------------------------------------------------------
            */

            ActivityLogService::log(
                'create',
                'Master Faskes',
                'Menambahkan faskes: ' . $faskes->namaFaskes,
                $faskes,
                null,
                $faskes->toArray()
            );


            return $faskes;
        });
    }


    /**
     * Update data
     */
    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            /*
            |--------------------------------------------------------------------------
            | Ambil data
            |--------------------------------------------------------------------------
            */

            $faskes = MasterFaskes::findOrFail($id);


            /*
            |--------------------------------------------------------------------------
            | Simpan data lama untuk Activity Log
            |--------------------------------------------------------------------------
            */

            $oldValues = $faskes->getOriginal();


            /*
            |--------------------------------------------------------------------------
            | Validasi kode faskes
            |--------------------------------------------------------------------------
            */

            $this->validateKodeFaskes(
                $data['kodeFaskes'],
                $faskes->id
            );


            /*
            |--------------------------------------------------------------------------
            | Update
            |--------------------------------------------------------------------------
            */

            $faskes->update([
                'kodeFaskes'    => $data['kodeFaskes'],
                'typeFaskes'    => $data['typeFaskes'],
                'kodePropinsi'  => $data['kodePropinsi'],
                'kodeKabupaten' => $data['kodeKabupaten'],
                'kodeKecamatan' => $data['kodeKecamatan'],
                'kepemilikan'   => $data['kepemilikan'] ?? null,
                'namaFaskes'    => $data['namaFaskes'],
            ]);


            /*
            |--------------------------------------------------------------------------
            | Ambil data terbaru
            |--------------------------------------------------------------------------
            */

            $faskes = $faskes->fresh([
                'type',
                'provinsi',
                'kota',
                'kecamatan',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Activity Log - UPDATE
            |--------------------------------------------------------------------------
            */

            ActivityLogService::log(
                'update',
                'Master Faskes',
                'Mengubah faskes: ' . $faskes->namaFaskes,
                $faskes,
                $oldValues,
                $faskes->toArray()
            );


            return $faskes;
        });
    }


    /**
     * Hapus data
     */
    public function delete($id)
    {
        return DB::transaction(function () use ($id) {

            /*
            |--------------------------------------------------------------------------
            | Ambil Faskes
            |--------------------------------------------------------------------------
            */

            $faskes = MasterFaskes::findOrFail($id);


            /*
            |--------------------------------------------------------------------------
            | Cek dependency Master Posyandu
            |--------------------------------------------------------------------------
            */

            $usedByPosyandu = MasterPosyandu::where(
                'kodeFaskes',
                $faskes->kodeFaskes
            )->exists();


            if ($usedByPosyandu) {

                throw ValidationException::withMessages([
                    'faskes' =>
                        'Faskes tidak dapat dihapus karena masih digunakan oleh data Posyandu.'
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Snapshot sebelum delete
            |--------------------------------------------------------------------------
            */

            $oldValues = $faskes->toArray();

            $namaFaskes = $faskes->namaFaskes;


            /*
            |--------------------------------------------------------------------------
            | Delete
            |--------------------------------------------------------------------------
            */

            $faskes->delete();


            /*
            |--------------------------------------------------------------------------
            | Activity Log - DELETE
            |--------------------------------------------------------------------------
            */

            ActivityLogService::log(
                'delete',
                'Master Faskes',
                'Menghapus faskes: ' . $namaFaskes,
                $faskes,
                $oldValues,
                null
            );


            return true;
        });
    }


    /**
     * Validasi kode faskes unik
     */
    protected function validateKodeFaskes(
        $kodeFaskes,
        $ignoreId = null
    ) {
        $query = MasterFaskes::where(
            'kodeFaskes',
            $kodeFaskes
        );


        if ($ignoreId) {

            $query->where(
                'id',
                '!=',
                $ignoreId
            );

        }


        if ($query->exists()) {

            throw ValidationException::withMessages([
                'kodeFaskes' =>
                    'Kode faskes sudah digunakan.',
            ]);

        }
    }
}

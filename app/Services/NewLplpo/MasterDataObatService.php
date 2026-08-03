<?php

namespace App\Services\NewLplpo;

use App\Models\NewLplpo\MasterDataObat;

class MasterDataObatService
{
    /**
     * DataTable query
     */
    public function datatable($request)
    {
        return MasterDataObat::query()
            ->select([
                'id',
                'kode_obat',
                'nama_obat',
                'satuan',
                'stok_minimum',
                'stok_optimum',
                'created_at'
            ]);
    }

    /**
     * Create
     */
    public function create(array $data)
    {
        return MasterDataObat::create($data);
    }

    /**
     * Find
     */
    public function find($id)
    {
        return MasterDataObat::findOrFail($id);
    }

    /**
     * Update
     */
    public function update($id, array $data)
    {
        $obat = MasterDataObat::findOrFail($id);

        $obat->update($data);

        return $obat;
    }

    /**
     * Delete
     */
    public function delete($id)
    {
        $obat = MasterDataObat::findOrFail($id);

        return $obat->delete();
    }
}

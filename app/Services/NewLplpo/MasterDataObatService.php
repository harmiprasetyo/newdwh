<?php

namespace App\Services\NewLplpo;

use App\Models\NewLplpo\MasterDataObat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterDataObatService
{
    /**
     * DataTable Master Obat
     */
    /**
 * DataTable khusus untuk Offcanvas LPLPO
 *
 * Master obat tetap berasal dari master_obat.
 * Parameter stok berasal dari master_stokminimal_obat
 * berdasarkan faskes dan tahun.
 */
public function datatableforcanvas(Request $request)
{
    $kodeFaskes = null;

    /*
    |--------------------------------------------------------------------------
    | KODE FASKES
    |--------------------------------------------------------------------------
    | User faskes mengambil dari user login.
    | User non-faskes dapat mengirim kodeFaskes dari request.
    |--------------------------------------------------------------------------
    */

    if (
        auth()->check() &&
        in_array(auth()->user()->groupid, [3, 5])
    ) {

        $kodeFaskes = auth()->user()->kodeFaskes;

    } else {

        $kodeFaskes = $request->input('kodeFaskes');

    }


    $tahun = $request->input('tahun');


    /*
    |--------------------------------------------------------------------------
    | QUERY
    |--------------------------------------------------------------------------
    */

    $query = MasterDataObat::query()

        ->leftJoin(
            'master_stokminimal_obat as stok',
            function ($join) use ($kodeFaskes, $tahun) {

                $join->on(
                    'master_obat.kode_obat',
                    '=',
                    'stok.kode_obat'
                );

                if (!empty($kodeFaskes)) {

                    $join->where(
                        'stok.kodeFaskes',
                        $kodeFaskes
                    );

                }

                if (!empty($tahun)) {

                    $join->where(
                        'stok.tahun',
                        $tahun
                    );

                }

            }
        )

        ->select([

            'master_obat.id',

            'master_obat.kode_obat',

            'master_obat.nama_obat',

            'master_obat.satuan',

            'master_obat.obat_napza',

            /*
            |--------------------------------------------------------------------------
            | Jika belum ada setting:
            | stok_minimal = 0
            | stok_optimum = 0
            |--------------------------------------------------------------------------
            */

            DB::raw(
                'COALESCE(stok.stok_minimal, 0) as stok_minimal'
            ),

            DB::raw(
                'COALESCE(stok.stok_optimum, 0) as stok_optimum'
            ),

            DB::raw(
                'COALESCE(stok.obat_esensial, "tidak") as obat_esensial'
            ),

            DB::raw(
                'COALESCE(stok.obat_formularium_puskesmas, "tidak") as obat_formularium_puskesmas'
            ),

        ]);


    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    $search = $request->input('search');

    if (is_array($search)) {

        $search = $search['value'] ?? '';

    }

    if (!empty($search)) {

        $query->where(function ($q) use ($search) {

            $q->where(
                'master_obat.kode_obat',
                'like',
                '%' . $search . '%'
            )

            ->orWhere(
                'master_obat.nama_obat',
                'like',
                '%' . $search . '%'
            );

        });

    }


    return $query;
}


    /**
     * DataTable
     */
    public function datatable(Request $request)
    {
        $query = MasterDataObat::query();

        /*
        |--------------------------------------------------------------------------
        | SELECT
        |--------------------------------------------------------------------------
        */

        $query->select([
            'id',
            'kode_obat',
            'nama_obat',
            'satuan',
            'obat_napza',
            'created_at',
            'updated_at'
        ]);


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        $search = $request->input('search');

        if (is_array($search)) {

            $search = $search['value'] ?? '';

        }

        if (!empty($search)) {

            $query->where(function ($q) use ($search) {

                $q->where(
                    'kode_obat',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'nama_obat',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'satuan',
                    'like',
                    '%' . $search . '%'
                );

            });

        }


        /*
        |--------------------------------------------------------------------------
        | EXCLUDE OBAT YANG SUDAH DIPILIH
        |--------------------------------------------------------------------------
        |
        | Bagian ini tetap dipertahankan karena digunakan oleh
        | Stok & Esensial.
        |
        */

        if (
            $request->boolean('exclude_stok_setting') &&
            $request->filled('tahun')
        ) {

            /*
            |--------------------------------------------------------------------------
            | KODE FASKES
            |--------------------------------------------------------------------------
            */

            if (
                in_array(
                    auth()->user()->groupid,
                    [3, 5]
                )
            ) {

                $kodeFaskes =
                    auth()->user()->kodeFaskes;

            } else {

                $kodeFaskes =
                    $request->kodeFaskes;

            }


            /*
            |--------------------------------------------------------------------------
            | EXCLUDE OBAT
            |--------------------------------------------------------------------------
            */

            if (!empty($kodeFaskes)) {

                $query->whereNotIn(
                    'kode_obat',
                    function ($subQuery) use (
                        $kodeFaskes,
                        $request
                    ) {

                        $subQuery
                            ->select('kode_obat')

                            ->from(
                                'master_stokminimal_obat'
                            )

                            ->where(
                                'kodeFaskes',
                                $kodeFaskes
                            )

                            ->where(
                                'tahun',
                                $request->tahun
                            )

                            /*
                            |--------------------------------------------------------------------------
                            | EDIT
                            |--------------------------------------------------------------------------
                            |
                            | Jangan exclude record yang sedang diedit.
                            |
                            */

                            ->when(
                                $request->filled('edit_id'),
                                function ($q) use ($request) {

                                    $q->where(
                                        'id',
                                        '!=',
                                        $request->edit_id
                                    );

                                }
                            );

                    }
                );

            }

        }


        return $query;
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
        $obat =
            MasterDataObat::findOrFail($id);

        $obat->update($data);

        return $obat->fresh();
    }


    /**
     * Delete
     */
    public function delete($id)
    {
        $obat =
            MasterDataObat::findOrFail($id);

        return $obat->delete();
    }
}

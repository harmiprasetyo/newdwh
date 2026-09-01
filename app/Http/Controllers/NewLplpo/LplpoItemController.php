<?php

namespace App\Http\Controllers\NewLplpo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NewLplpo\ItemService;
use App\Models\NewLplpo\Item;

class LplpoItemController extends Controller
{
    protected ItemService $service;

    public function __construct(ItemService $service)
    {
        $this->service = $service;
    }

    /*
    |--------------------------------------------------------------------------
    | DEFAULT VALUE
    |--------------------------------------------------------------------------
    */

    public function defaultValue(Request $request)
    {
        $request->validate([
            'report_id' => 'required|integer',
            'kode_obat' => 'required|string',
            'program_id' => 'required|integer',
        ]);

        return response()->json(
            $this->service->defaultValue(
                $request->report_id,
                $request->kode_obat,
                $request->program_id
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LIST
    |--------------------------------------------------------------------------
    */

    public function list($reportId)
    {
        return response()->json(
            $this->service->datatable($reportId)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        try {

            $item = $this->service->find($id);

            return response()->json([
                'success' => true,
                'data' => $item
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Data item tidak ditemukan.',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $request->validate([
            'program_id' => 'required|integer',
            'kode_obat' => 'required|string',
            'nama_obat' => 'required|string',
            'satuan' => 'required|string',
        ]);

        try {

            $item = $this->service->update(
                $id,
                $request->all()
            );

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil diupdate.',
                'data' => $item
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate item.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'report_id' => 'required|integer',
            'program_id' => 'required|integer',
            'kode_obat' => 'required|string',
            'nama_obat' => 'required|string',
            'satuan' => 'required|string',
        ]);

        try {

            $item = $this->service->create(
                $request->all()
            );

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil disimpan.',
                'data' => $item
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan item.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        try {

            $item = Item::findOrFail($id);

            $this->service->delete($item);

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil dihapus.'
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus item.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | COPY PREVIOUS MONTH
    |--------------------------------------------------------------------------
    */

    public function copyPreviousMonth(Request $request)
    {
        $request->validate([
            'report_id' => 'required|integer'
        ]);

        $items = $this->service->copyPreviousMonthItems(
            $request->report_id
        );

        return response()->json([
            'success' => true,

            'message' => $items->count() > 0
                ? $items->count() . ' item bulan sebelumnya berhasil dimasukkan.'
                : 'Tidak ada data bulan sebelumnya yang perlu dimasukkan.',

            'count' => $items->count()
        ]);
    }
}

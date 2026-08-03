<?php

namespace App\Http\Controllers\NewLplpo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NewLplpo\MasterDataObatService;

class MasterDataObatController extends Controller
{
    protected $service;

    public function __construct(
        MasterDataObatService $service
    ) {
        $this->service = $service;
    }

    /**
     * Halaman Master Data Obat
     */
    public function index()
    {
        return view('newlplpo.masterdataobat.index');
    }

    /**
     * DataTable
     */
    public function datatable(Request $request)
    {
        $query = $this->service->datatable($request);

        return datatables()
            ->eloquent($query)

            ->addIndexColumn()

            ->addColumn('aksi', function ($item) {

                return '
                    <div class="btn-group">

                        <button
                            type="button"
                            class="btn btn-sm btn-warning btn-edit-obat"
                            data-id="' . $item->id . '">

                            <i class="bi bi-pencil"></i>

                        </button>

                        <button
                            type="button"
                            class="btn btn-sm btn-danger btn-delete-obat"
                            data-id="' . $item->id . '"
                            data-nama="' . e($item->nama_obat) . '">

                            <i class="bi bi-trash"></i>

                        </button>

                    </div>
                ';
            })

            ->rawColumns([
                'aksi'
            ])

            ->make(true);
    }
}

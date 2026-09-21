<?php

namespace App\Http\Controllers\NewLplpo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NewLplpo\MasterDataObatService;
use Illuminate\Validation\Rule;

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
    /**
 * DataTable khusus Offcanvas LPLPO
 */
/**
 * DataTable khusus Offcanvas LPLPO
 */
/**
 * DataTable khusus Offcanvas LPLPO
 */
public function datatableforcanvas(Request $request)
{
    $query = $this->service->datatableforcanvas($request);

    return datatables()
        ->eloquent($query)
        ->addIndexColumn()
        ->make(true);
}

    public function datatable(Request $request)
    {
        $query = $this->service->datatable($request);

        return datatables()
            ->eloquent($query)

            ->addIndexColumn()

            ->editColumn(
                'obat_napza',
                function ($row) {

                    if ($row->obat_napza === 'ya') {

                        return '
                            <span class="badge bg-danger">
                                Ya
                            </span>
                        ';

                    }

                    return '
                        <span class="badge bg-secondary">
                            Tidak
                        </span>
                    ';
                }
            )
              ->setRowClass(function ($item) {

            return $item->obat_napza === 'ya'
                ? 'table-danger'
                : '';
        })


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
                'obat_napza',
                'aksi'
            ])

            ->make(true);
    }

    /**
     * Store
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_obat' => [
                'required',
                'string',
                'max:50',
                'unique:master_obat,kode_obat'
            ],

            'nama_obat' => [
                'required',
                'string',
                'max:255'
            ],

            'satuan' => [
                'required',
                'string',
                'max:100'
            ],

            'obat_napza' => [
                'required',
                'in:ya,tidak'
            ],
        ]);

        $obat = $this->service->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data obat berhasil ditambahkan.',
            'data' => $obat
        ]);
    }

    /**
     * Detail
     */
    public function show($id)
    {
        $obat = $this->service->find($id);

        return response()->json([
            'success' => true,
            'data' => $obat
        ]);
    }

    /**
     * Update
     */
    public function update(Request $request, $id)
{
    $request->validate([
        'kode_obat' => [
            'required',
            'string',
            'max:50',
            Rule::unique('master_obat', 'kode_obat')
                ->ignore($id),
        ],

        'nama_obat' => [
            'required',
            'string',
            'max:255',
        ],

        'satuan' => [
            'required',
            'string',
            'max:255',
        ],

        'obat_napza' => [
            'required',
            'in:ya,tidak',
        ],
    ]);

    $obat = $this->service->update(
        $id,
        $request->only([
            'kode_obat',
            'nama_obat',
            'satuan',
            'obat_napza',
        ])
    );

    return response()->json([
        'success' => true,
        'message' => 'Data obat berhasil diperbarui.',
        'data' => $obat,
    ]);
}

    /**
     * Delete
     */
    public function destroy($id)
    {
        $this->service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Data obat berhasil dihapus.'
        ]);
    }
}

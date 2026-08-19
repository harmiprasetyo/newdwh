<?php

namespace App\Http\Controllers\AdminPanel\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\MasterFaskes;
use App\Models\Master\ListTypeFaskes;
use App\Services\AdminPanel\MasterFaskesService;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;

use Yajra\DataTables\Facades\DataTables;
use App\Services\ActivityLogService;

class MasterFaskesController extends Controller
{
    protected MasterFaskesService $service;

    public function __construct(MasterFaskesService $service)
    {
        $this->service = $service;
    }

    /**
     * Halaman utama
     */
   public function index()
{
    $types = ListTypeFaskes::orderBy('typeFaskes')->get();

    return view(
        'adminpanel.master.masterfaskes.index',
        compact('types')
    );
}

    /**
     * DataTables
     */
 public function datatable(Request $request)
{
    $query = MasterFaskes::with([
        'type',
        'provinsi',
        'kota',
        'kecamatan',
    ]);

    return DataTables::of($query)

        ->addIndexColumn()

        ->addColumn('type_faskes', function ($row) {

            return optional($row->type)->typeFaskes ?? '-';

        })

        ->addColumn('provinsi', function ($row) {

            return optional($row->provinsi)->name ?? '-';

        })

        ->addColumn('kota', function ($row) {

            return optional($row->kota)->name ?? '-';

        })

        ->addColumn('kecamatan', function ($row) {

            return optional($row->kecamatan)->name ?? '-';

        })

        ->addColumn('action', function ($row) {

            return '
                <button
                    type="button"
                    class="btn btn-sm btn-warning btn-edit"
                    data-id="' . $row->id . '"
                    title="Edit">

                    <i class="fas fa-edit"></i>

                </button>

                <button
                    type="button"
                    class="btn btn-sm btn-danger btn-delete"
                    data-id="' . $row->id . '"
                    data-name="' . e($row->namaFaskes) . '"
                    title="Hapus">

                    <i class="fas fa-trash"></i>

                </button>
            ';

        })

        ->rawColumns([
            'action'
        ])

        ->make(true);
}
    /**
     * Detail data
     */
    public function show($id)
    {
        $data = $this->service->find($id);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Simpan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kodeFaskes' => [
                'required',
                'string',
                'max:50',
                'unique:master_faskes,kodeFaskes',
            ],

            'typeFaskes' => [
                'required',
            ],

            'kodePropinsi' => [
                'required',
            ],

            'kodeKabupaten' => [
                'required',
            ],

            'kodeKecamatan' => [
                'required',
            ],

            'kepemilikan' => [
                'nullable',
                'string',
                'max:100',
            ],

            'namaFaskes' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $data = $this->service->store($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data faskes berhasil disimpan.',
            'data' => $data,
        ]);
    }

    /**
     * Update
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'kodeFaskes' => [
                'required',
                'string',
                'max:50',
                Rule::unique('master_faskes', 'kodeFaskes')
                    ->ignore($id),
            ],

            'typeFaskes' => [
                'required',
            ],

            'kodePropinsi' => [
                'required',
            ],

            'kodeKabupaten' => [
                'required',
            ],

            'kodeKecamatan' => [
                'required',
            ],

            'kepemilikan' => [
                'nullable',
                'string',
                'max:100',
            ],

            'namaFaskes' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $data = $this->service->update($id, $validated);

        return response()->json([
            'success' => true,
            'message' => 'Data faskes berhasil diperbarui.',
            'data' => $data,
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
            'message' => 'Data faskes berhasil dihapus.',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | MASTER COMBO
    |--------------------------------------------------------------------------
    */

    /**
     * List tipe faskes
     */
    public function types()
{
    $data = ListTypeFaskes::query()
        ->select([
            'id',
            'typeFaskes',
        ])
        ->orderBy('typeFaskes')
        ->get();

    return response()->json([
        'success' => true,
        'data' => $data,
    ]);
}

    /**
     * List provinsi
     */
    public function provinces()
    {
        $data = Province::query()
            ->select([
                'code',
                'name',
            ])
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * List kota berdasarkan provinsi
     */
    public function cities(Request $request)
    {
        $request->validate([
            'province_code' => [
                'required',
                'string',
            ],
        ]);

        $data = City::query()
            ->select([
                'code',
                'province_code',
                'name',
            ])
            ->where(
                'province_code',
                $request->province_code
            )
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * List kecamatan berdasarkan kota
     */
    public function districts(Request $request)
    {
        $request->validate([
            'city_code' => [
                'required',
                'string',
            ],
        ]);

        $data = District::query()
            ->select([
                'code',
                'city_code',
                'name',
            ])
            ->where(
                'city_code',
                $request->city_code
            )
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * List faskes berdasarkan kecamatan
     */
    public function facilities(Request $request)
{
    $user = auth()->user();

    $kodeKota = $user->kodeKota;

    if (!$kodeKota) {
        return response()->json([
            'success' => false,
            'message' => 'User belum memiliki kode kota.',
            'data' => [],
        ], 422);
    }

    $data = MasterFaskes::query()
        ->where('kodeKabupaten', $kodeKota)
        ->orderBy('namaFaskes')
        ->get([
            'id',
            'kodeFaskes',
            'namaFaskes',
            'typeFaskes',
            'kodePropinsi',
            'kodeKabupaten',
            'kodeKecamatan',
            'kepemilikan',
        ]);

    return response()->json([
        'success' => true,
        'data' => $data,
    ]);
}

    /**
     * Endpoint hierarki sekaligus.
     *
     * Jika dikirim:
     * province_code
     * city_code
     * district_code
     *
     * maka masing-masing level akan dikembalikan.
     */
    public function hierarchy(Request $request)
    {
        $request->validate([
            'province_code' => [
                'nullable',
                'string',
            ],

            'city_code' => [
                'nullable',
                'string',
            ],

            'district_code' => [
                'nullable',
                'string',
            ],
        ]);

        $result = [
            'provinces' => [],
            'cities' => [],
            'districts' => [],
            'facilities' => [],
        ];

        /*
        |--------------------------------------------------------------------------
        | Provinsi
        |--------------------------------------------------------------------------
        */

        $result['provinces'] = Province::query()
            ->select([
                'code',
                'name',
            ])
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Kota
        |--------------------------------------------------------------------------
        */

        if ($request->filled('province_code')) {

            $result['cities'] = City::query()
                ->select([
                    'code',
                    'province_code',
                    'name',
                ])
                ->where(
                    'province_code',
                    $request->province_code
                )
                ->orderBy('name')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Kecamatan
        |--------------------------------------------------------------------------
        */

        if ($request->filled('city_code')) {

            $result['districts'] = District::query()
                ->select([
                    'code',
                    'city_code',
                    'name',
                ])
                ->where(
                    'city_code',
                    $request->city_code
                )
                ->orderBy('name')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Faskes
        |--------------------------------------------------------------------------
        */

        if ($request->filled('district_code')) {

            $result['facilities'] = MasterFaskes::query()
                ->select([
                    'id',
                    'kodeFaskes',
                    'namaFaskes',
                    'typeFaskes',
                    'kodePropinsi',
                    'kodeKabupaten',
                    'kodeKecamatan',
                ])
                ->where(
                    'kodeKecamatan',
                    $request->district_code
                )
                ->orderBy('namaFaskes')
                ->get();
        }

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }
}

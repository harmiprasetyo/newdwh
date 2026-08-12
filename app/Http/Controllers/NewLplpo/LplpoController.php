<?php

namespace App\Http\Controllers\NewLplpo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\NewLplpo\ReportService;

use App\Models\NewLplpo\Report;
use App\Services\NewLplpo\ItemService;
use App\Models\NewLplpo\Program;
use App\Models\Master\MasterFaskes;
use Carbon\Carbon;
use App\Models\ListTypeFaskes;
use Yajra\DataTables\Facades\DataTables;
use App\Models\NewLplpo\Item;


use App\Models\NewLplpo\MasterDataObat;
use App\Models\NewLplpo\StokMinimalObat;
use Illuminate\Support\Facades\DB;


class LplpoController extends Controller
{

    protected ReportService $service;
    protected ItemService $itemService;

    public function __construct(ReportService $service,ItemService $itemService){
    $this->service=$service;
    $this->itemService=$itemService;
}


private function getPreviousMonthItems(
    string $kodeFaskes,
    int $bulan,
    int $tahun
) {
    /*
    |--------------------------------------------------------------------------
    | TENTUKAN BULAN SEBELUMNYA
    |--------------------------------------------------------------------------
    */

    $periodeSekarang = Carbon::create(
        $tahun,
        $bulan,
        1
    );

    $periodeSebelumnya = $periodeSekarang
        ->copy()
        ->subMonth();

    $bulanSebelumnya = $periodeSebelumnya->month;
    $tahunSebelumnya = $periodeSebelumnya->year;


    /*
    |--------------------------------------------------------------------------
    | CARI SEMUA REPORT BULAN SEBELUMNYA
    |--------------------------------------------------------------------------
    */

    $reportIds = Report::query()

        ->where('kode_faskes', $kodeFaskes)

        ->where('bulan', $bulanSebelumnya)

        ->where('tahun', $tahunSebelumnya)

        ->whereIn('report_status', [
            'SUBMITED',
            'VERIFIED',
            'FINAL'
        ])

        ->pluck('id');


    /*
    |--------------------------------------------------------------------------
    | TIDAK ADA REPORT
    |--------------------------------------------------------------------------
    */

    if ($reportIds->isEmpty()) {

        return collect();

    }


    /*
    |--------------------------------------------------------------------------
    | AMBIL ITEM
    |--------------------------------------------------------------------------
    |
    | Karena bisa ada lebih dari satu laporan,
    | maka kita GROUP BY kode_obat.
    |
    */

    $items = Item::query()

        ->whereIn('report_id', $reportIds)

        ->select(
            'kode_obat',
            DB::raw('MAX(nama_obat) as nama_obat'),
            DB::raw('MAX(satuan) as satuan'),

            /*
            |--------------------------------------------------------------------------
            | STOK AKHIR BULAN LALU
            |--------------------------------------------------------------------------
            */

            DB::raw('SUM(stok_akhir_program_pkd) as stok_akhir_pkd'),
            DB::raw('SUM(stok_akhir_jkn) as stok_akhir_jkn'),

            /*
            |--------------------------------------------------------------------------
            | TOTAL PEMBERIAN BULAN LALU
            |--------------------------------------------------------------------------
            */

            DB::raw('SUM(pemberian_program_pkd) as pemberian_pkd'),
            DB::raw('SUM(pemberian_jkn) as pemberian_jkn'),

            /*
            |--------------------------------------------------------------------------
            | TOTAL PEMAKAIAN BULAN LALU
            |--------------------------------------------------------------------------
            */

            DB::raw('SUM(pemakaian_program_pkd) as pemakaian_pkd'),
            DB::raw('SUM(pemakaian_jkn) as pemakaian_jkn'),

            /*
            |--------------------------------------------------------------------------
            | PROGRAM
            |--------------------------------------------------------------------------
            |
            | program_id tetap diperlukan oleh struktur database.
            | Kita ambil salah satu program.
            |
            */

            DB::raw('MIN(program_id) as program_id'),
            DB::raw('MAX(program_name) as program_name')
        )

        ->groupBy('kode_obat')

        ->get();


    /*
    |--------------------------------------------------------------------------
    | AMBIL MASTER STOK MINIMAL
    |--------------------------------------------------------------------------
    */

    $kodeObat = $items
        ->pluck('kode_obat')
        ->filter()
        ->values();


    $stokMinimal = StokMinimalObat::query()

        ->where('kodeFaskes', $kodeFaskes)

        ->where('tahun', $tahun)

        ->whereIn('kode_obat', $kodeObat)

        ->get()

        ->keyBy('kode_obat');


    /*
    |--------------------------------------------------------------------------
    | BENTUK DATA UNTUK ITEM LPLPO BARU
    |--------------------------------------------------------------------------
    */

    return $items->map(function ($item) use (
        $stokMinimal
    ) {

        $masterStok =
            $stokMinimal->get(
                $item->kode_obat
            );


        /*
        |--------------------------------------------------------------------------
        | STOK AWAL
        |--------------------------------------------------------------------------
        |
        | Stok awal = stok akhir bulan sebelumnya
        |
        */

        $stokAwalPkd =
            (int) $item->stok_akhir_pkd;

        $stokAwalJkn =
            (int) $item->stok_akhir_jkn;


        /*
        |--------------------------------------------------------------------------
        | PENERIMAAN
        |--------------------------------------------------------------------------
        |
        | Penerimaan bulan sekarang =
        | total pemberian bulan sebelumnya.
        |
        */

        $penerimaanPkd =
            (int) $item->pemberian_pkd;

        $penerimaanJkn =
            (int) $item->pemberian_jkn;


        /*
        |--------------------------------------------------------------------------
        | PERSEDIAAN
        |--------------------------------------------------------------------------
        */

        $persediaanPkd =
            $stokAwalPkd +
            $penerimaanPkd;

        $persediaanJkn =
            $stokAwalJkn +
            $penerimaanJkn;


        /*
        |--------------------------------------------------------------------------
        | PEMAKAIAN
        |--------------------------------------------------------------------------
        */

        $pemakaianPkd =
            (int) $item->pemakaian_pkd;

        $pemakaianJkn =
            (int) $item->pemakaian_jkn;


        /*
        |--------------------------------------------------------------------------
        | EXPIRED
        |--------------------------------------------------------------------------
        */

        $expiredPkd = 0;
        $expiredJkn = 0;


        /*
        |--------------------------------------------------------------------------
        | STOK AKHIR
        |--------------------------------------------------------------------------
        */

        $stokAkhirPkd =
            $persediaanPkd -
            $pemakaianPkd;

        $stokAkhirJkn =
            $persediaanJkn -
            $pemakaianJkn;


        return [

            'program_id' =>
                $item->program_id,

            'program_name' =>
                $item->program_name,

            'kode_obat' =>
                $item->kode_obat,

            'nama_obat' =>
                $item->nama_obat,

            'satuan' =>
                $item->satuan,

            /*
            |--------------------------------------------------------------------------
            | STOK AWAL
            |--------------------------------------------------------------------------
            */

            'stok_awal_progam_pkd' =>
                $stokAwalPkd,

            'stok_awal_jkn' =>
                $stokAwalJkn,

            /*
            |--------------------------------------------------------------------------
            | PENERIMAAN
            |--------------------------------------------------------------------------
            */

            'penerimaan_program_pkd' =>
                $penerimaanPkd,

            'penerimaan_jkn' =>
                $penerimaanJkn,

            /*
            |--------------------------------------------------------------------------
            | PERSEDIAAN
            |--------------------------------------------------------------------------
            */

            'persediaan_program_pkd' =>
                $persediaanPkd,

            'persediaan_jkn' =>
                $persediaanJkn,

            /*
            |--------------------------------------------------------------------------
            | PEMAKAIAN
            |--------------------------------------------------------------------------
            */

            'pemakaian_program_pkd' =>
                $pemakaianPkd,

            'pemakaian_jkn' =>
                $pemakaianJkn,

            /*
            |--------------------------------------------------------------------------
            | EXPIRED
            |--------------------------------------------------------------------------
            */

            'item_expired_pkd' =>
                $expiredPkd,

            'item_expired_jkn' =>
                $expiredJkn,

            /*
            |--------------------------------------------------------------------------
            | STOK AKHIR
            |--------------------------------------------------------------------------
            */

            'stok_akhir_program_pkd' =>
                $stokAkhirPkd,

            'stok_akhir_jkn' =>
                $stokAkhirJkn,

            /*
            |--------------------------------------------------------------------------
            | STOK MINIMUM
            |--------------------------------------------------------------------------
            */

            'stok_minimum' =>
                $masterStok
                    ? (int) $masterStok->stok_minimal
                    : 0,

            /*
            |--------------------------------------------------------------------------
            | STOK OPTIMUM
            |--------------------------------------------------------------------------
            */

            'stok_optimum' =>
                $masterStok
                    ? (int) $masterStok->stok_optimum
                    : 0,

            /*
            |--------------------------------------------------------------------------
            | PERMINTAAN
            |--------------------------------------------------------------------------
            */

            'permintaan' => 0,

            /*
            |--------------------------------------------------------------------------
            | PEMBERIAN
            |--------------------------------------------------------------------------
            */

            'pemberian_program_pkd' => 0,

            'pemberian_jkn' => 0,

            /*
            |--------------------------------------------------------------------------
            | OBAT ESENSIAL
            |--------------------------------------------------------------------------
            */

            'obat_esensial' =>
                $masterStok &&
                $masterStok->obat_esensial === 'oe'
                    ? 'OE'
                    : 'NE',
        ];
    });
}

public function detail($id)
{
    $report = Report::findOrFail($id);

    $items = $this->itemService->listByReport($id);

    $faskes = MasterFaskes::with([
        'type',
        'provinsi',
        'kota',
        'kecamatan'
    ])
    ->where('kodeFaskes',$report->kode_faskes)
    ->firstOrFail();

    return view('newlplpo.detail',[
        'report'=>$report,
        'items'=>$items,
        'faskes'=>$faskes
    ]);
}





public function laporan()
{
    return view('newlplpo.laporan');
}

public function laporanDatatable(Request $request)
{
    $request->merge([

        'bulan' => $request->bulan ?? Carbon::now()->month,

        'tahun' => $request->tahun ?? Carbon::now()->year,

    ]);

    $data = $this->service->laporan($request);

    return DataTables::of($data)

        ->addIndexColumn()

        ->editColumn('created_at', function ($row) {

            return $row->created_at
                ->format('d-m-Y H:i');

        })

        ->addColumn('status_badge', function ($row) {

            $color = match($row->report_status){

                'DRAFT'      => 'warning',

                'SUBMITED'  => 'info',

                'VERIFIED'   => 'primary',
                'REJECTED'=>'danger',

                'FINAL'   => 'success',

                default       => 'dark'

            };
            $status = match($row->report_status){
                'DRAFT' =>'DRAFT',
                'SUBMITED'=>'TERKIRIM',
                'VERIFIED' =>'TERVERIFIKASI',
                'REJECTED'=>'DITOLAK',
                'FINAL'=>'SELESAI',
                default => 'NEW'

            };

            return '<span class="badge bg-'.$color.'">'.
                    $status.
                   '</span>';

        })

    /*    ->addColumn('action', function ($row){

            return '

            <a href="'.route('newlplpo.edit',$row->id).'"
               class="btn btn-sm btn-primary">

                <i class="bi bi-eye"></i>

            </a>

            ';

        })*/

            ->addColumn('action', function ($row){

    if(in_array($row->report_status,['DRAFT'])){

         $btn = '

        <a href="'.route('newlplpo.edit',$row->id).'"
            class="btn btn-warning btn-sm"
            title="Edit">

            <i class="bi bi-pencil-square"></i>

        </a>

        ';

         $btn .= '

            <button
                class="btn btn-danger btn-sm btnDelete"
                data-id="'.$row->id.'"
                title="Hapus">

                <i class="bi bi-trash"></i>

            </button>

            ';

             return $btn;

    }

    return '

    <a
        href="'.route('newlplpo.detail',$row->id).'"
        class="btn btn-primary btn-sm"
        title="Detail">

        <i class="bi bi-eye"></i>

    </a>

    ';

})

        ->rawColumns([
            'status_badge',
            'action'
        ])

        ->make(true);
}


    /**
     * daftar lplpo
     */
    public function index(Request $request)
    {

        $reports=$this->service->list($request);

        return view(
            'newlplpo.listlplpo',
            compact('reports')
        );

    }


    public function items($reportId)
{
    $items = $this->itemService->listByReport($reportId);

    return view('newlplpo.partials.items_table', compact('items'));
}

public function lastlplpo(){

}

    /**
     * form membuat lplpo
     */


public function create()
{
    $programs = Program::orderBy('program_name')->get();

    $kodeFaskes = session('kodeFaskes');

    $faskes = MasterFaskes::with([
        'type',
        'provinsi',
        'kota',
        'kecamatan'
    ])
    ->where('kodeFaskes',$kodeFaskes)
    ->firstOrFail();

    return view('newlplpo.buatlplpo',[

        'programs'=>$programs,

        'nomorLplpo'=>'LPLPO-'.$kodeFaskes.'-'.Carbon::now()->format('YdmHis'),

        'faskes'=>$faskes

    ]);

}

    /**
     * simpan header lplpo
     */
  public function store(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | AMBIL FASKES DARI SESSION
    |--------------------------------------------------------------------------
    */

    $kodeFaskes = session('kodeFaskes');

    $namaFaskes = MasterFaskes::where(
        'kodeFaskes',
        $kodeFaskes
    )->value('namaFaskes');


    /*
    |--------------------------------------------------------------------------
    | VALIDASI
    |--------------------------------------------------------------------------
    */

    $request->validate([

        'bulan' =>
            'required|integer|min:1|max:12',

        'tahun' =>
            'required|integer|min:2000|max:2100',

        'nomor_lplpo' =>
            'required|string|max:255',

    ]);


    /*
    |--------------------------------------------------------------------------
    | DATA HEADER
    |--------------------------------------------------------------------------
    */

    $data = [

        'kode_faskes' =>
            $kodeFaskes,

        'nama_faskes' =>
            $namaFaskes,

        'bulan' =>
            $request->bulan,

        'tahun' =>
            $request->tahun,

        'nomor_lplpo' =>
            $request->nomor_lplpo,

    ];


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    $report = $this->service->create($data);


    /*
    |--------------------------------------------------------------------------
    | REDIRECT KE EDIT
    |--------------------------------------------------------------------------
    */

    return redirect()

        ->route(
            'newlplpo.edit',
            $report->id
        )

        ->with(
            'success',
            'Laporan berhasil dibuat.'
        );
}

    /**
     * edit laporan
     */
 public function edit($id)
{
    $report = Report::findOrFail($id);


    /*
    |--------------------------------------------------------------------------
    | COPY ITEM BULAN SEBELUMNYA
    |--------------------------------------------------------------------------
    */

    if (
        $report->report_status === 'DRAFT' &&
        !Item::where(
            'report_id',
            $report->id
        )->exists()
    ) {

        $this->itemService->copyPreviousMonthItems(
            $report->id
        );

    }


    /*
    |--------------------------------------------------------------------------
    | AMBIL ITEM
    |--------------------------------------------------------------------------
    */

    $items = $this->itemService->listByReport($id);


    $programs =
        Program::orderBy('program_name')->get();


    $faskes =
        MasterFaskes::with([
            'type',
            'provinsi',
            'kota',
            'kecamatan'
        ])

        ->where(
            'kodeFaskes',
            $report->kode_faskes
        )

        ->firstOrFail();


    $mode = 'edit';


    return view(
        'newlplpo.buatlplpo',
        [

            'report' =>
                $report,

            'items' =>
                $items,

            'programs' =>
                $programs,

            'faskes' =>
                $faskes,

            'nomorLplpo' =>
                $report->nomor_lplpo,

            'mode' =>
                $mode

        ]
    );
}

    /**
     * update laporan
     */
public function update(Request $request, $id)
{
    $report = Report::findOrFail($id);


    /*
    |--------------------------------------------------------------------------
    | SUBMIT LAPORAN
    |--------------------------------------------------------------------------
    */

    if ($request->report_status === 'SUBMITED') {

        if (!$report->kunjungan()->exists()) {

            if ($request->ajax()) {

                return response()->json([
                    'success' => false,
                    'message' =>
                        'Laporan tidak dapat dikirim karena data kunjungan belum diinput.'
                ], 422);

            }

            return back()->with(
                'error',
                'Laporan tidak dapat dikirim karena data kunjungan belum diinput.'
            );
        }


        $this->service->update(
            $report,
            [
                'report_status' => 'SUBMITED'
            ]
        );


        return redirect()
            ->route(
                'newlplpo.edit',
                $report->id
            )
            ->with(
                'success',
                'Laporan berhasil dikirim.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT HEADER
    |--------------------------------------------------------------------------
    */

    $request->validate([
        'bulan' => 'required|integer|min:1|max:12',
        'tahun' => 'required|integer|min:2000|max:2100',
    ]);


    $report = $this->service->update(
        $report,
        [
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
        ]
    );


    if ($request->ajax()) {

        return response()->json([
            'success' => true,
            'message' => 'Header berhasil diupdate.',
            'data' => $report
        ]);

    }


    return back()->with(
        'success',
        'Data berhasil diupdate.'
    );
}
    /**
     * arsip
     */
    public function arsip(Request $request)
    {

        $reports=Report::

            where('report_status','FINAL')

            ->paginate(20);

        return view(

            'newlplpo.arsiplplpo',

            compact('reports')

        );

    }

    /**
     * pemberian obat
     */
    public function pemberian()
    {

        return view(

            'newlplpo.pemberian'

        );

    }

    /**
     * hapus
     */
    public function destroy($id)
    {

        $report=Report::findOrFail($id);

        $this->service->delete($report);

        return back()

            ->with(

                'success',

                'Laporan berhasil dihapus.'

            );

    }


    public function storeItem(Request $request)
{
    $request->validate([
        'report_id'  => 'required',
        'program_id' => 'required',
        'kode_obat'  => 'required',
        'nama_obat'  => 'required'
    ]);

    $item = $this->itemService->create($request->all());

    if ($request->ajax()) {

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil disimpan',
            'data'    => $item
        ]);

    }

    return back()->with('success','Item berhasil disimpan');
}

public function updateItem(Request $request,$id)
{

    $item=Item::findOrFail($id);

    $this->itemService->update(

        $item,

        $request->all()

    );

    return back()

        ->with(

            'success',

            'Item berhasil diupdate.'

        );

}


public function destroyItem($id)
{

    $item=Item::findOrFail($id);

    $this->itemService->delete($item);

    return back()

        ->with(

            'success',

            'Item berhasil dihapus.'

        );

}


public function rekapData(Request $request)
{
    $request->validate([
        'bulan_mulai' => 'required|integer|min:1|max:12',
        'tahun_mulai' => 'required|integer',
        'bulan_sampai' => 'required|integer|min:1|max:12',
        'tahun_sampai' => 'required|integer',
        'kode_faskes' => 'nullable|string',
    ]);

    $mulai = ($request->tahun_mulai * 100) + $request->bulan_mulai;

    $sampai = ($request->tahun_sampai * 100) + $request->bulan_sampai;

    if ($mulai > $sampai) {
        return response()->json([
            'success' => false,
            'message' => 'Periode mulai tidak boleh lebih besar dari periode sampai.'
        ], 422);
    }

    $query = Report::query()
        ->where('report_status', 'FINAL')
        ->whereRaw(
            '(tahun * 100 + bulan) BETWEEN ? AND ?',
            [$mulai, $sampai]
        );

    if ($request->filled('kode_faskes')) {
        $query->where(
            'kode_faskes',
            $request->kode_faskes
        );
    }

    $reports = $query->get();

    $reportIds = $reports->pluck('id');

    $items = Item::with('program')
        ->whereIn('report_id', $reportIds)
        ->get();

    /*
    |--------------------------------------------------------------------------
    | REKAP ITEM
    |--------------------------------------------------------------------------
    */

    $rekap = $items
        ->groupBy(function ($item) {

            return implode('|', [
                $item->program_id,
                $item->kode_obat
            ]);

        })
        ->map(function ($group) {

            $first = $group->first();

            return [
                'program_id' => $first->program_id,

                'program_name' =>
                    optional($first->program)->program_name
                    ?? 'Non Program',

                'kode_obat' => $first->kode_obat,

                'nama_obat' => $first->nama_obat,

                'satuan' => $first->satuan,

                'stok_awal_program_pkd' =>
                    $group->sum('stok_awal_program_pkd'),

                'stok_awal_jkn' =>
                    $group->sum('stok_awal_jkn'),

                'penerimaan_program_pkd' =>
                    $group->sum('penerimaan_program_pkd'),

                'penerimaan_jkn' =>
                    $group->sum('penerimaan_jkn'),

                'persediaan_program_pkd' =>
                    $group->sum('persediaan_program_pkd'),

                'persediaan_jkn' =>
                    $group->sum('persediaan_jkn'),

                'pemakaian_program_pkd' =>
                    $group->sum('pemakaian_program_pkd'),

                'pemakaian_jkn' =>
                    $group->sum('pemakaian_jkn'),

                'item_expired_pkd' =>
                    $group->sum('item_expired_pkd'),

                'item_expired_jkn' =>
                    $group->sum('item_expired_jkn'),

                'stok_akhir_program_pkd' =>
                    $group->sum('stok_akhir_program_pkd'),

                'stok_akhir_jkn' =>
                    $group->sum('stok_akhir_jkn'),

                'stok_minimum' =>
                    $group->max('stok_minimum'),

                'stok_optimum' =>
                    $group->max('stok_optimum'),

                'permintaan' =>
                    $group->sum('permintaan'),

                'pemberian_program_pkd' =>
                    $group->sum('pemberian_program_pkd'),

                'pemberian_jkn' =>
                    $group->sum('pemberian_jkn'),
            ];

        })
        ->values();

    return response()->json([

        'success' => true,

        'bulan_mulai' =>
            (int) $request->bulan_mulai,

        'tahun_mulai' =>
            (int) $request->tahun_mulai,

        'bulan_sampai' =>
            (int) $request->bulan_sampai,

        'tahun_sampai' =>
            (int) $request->tahun_sampai,

        'jumlah_laporan' =>
            $reports->count(),

        'items' =>
            $rekap

    ]);
}

}

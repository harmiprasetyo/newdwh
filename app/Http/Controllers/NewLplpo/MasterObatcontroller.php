<?php

namespace App\Http\Controllers\NewLplpo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\NewLplpo\MasterObatService;

class MasterObatController extends Controller
{
    protected $service;

    public function __construct(
        MasterObatService $service
    ){
        $this->service = $service;
    }


public function datatable(Request $request)
{
    $query = $this->service->datatable($request);

    return datatables()
        ->eloquent($query)

        ->addColumn('aksi', function ($item) {

            return '
<button
class="btn btn-success btn-sm pilih-obat"

data-id="'.$item->id.'"

data-kode="'.$item->kode_obat.'"

data-nama="'.$item->nama_obat.'"

data-satuan="'.$item->satuan.'"

data-min="'.$item->stok_minimum.'"

data-opt="'.$item->stok_optimum.'">

Pilih

</button>';

        })

        ->rawColumns(['aksi'])

        ->make(true);
}

}

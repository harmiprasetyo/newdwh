<?php
 namespace App\Services\NewLplpo;

use App\Models\NewLplpo\MasterObat;
use Illuminate\Http\Request;

class MasterObatService
{
    public function datatable(Request $request)
    {
        $query = MasterObat::query();

        if ($request->filled('search.value')) {

            $keyword = $request->input('search.value');

            $query->where(function ($q) use ($keyword) {

                $q->where('kode_obat', 'like', "%{$keyword}%")
                  ->orWhere('nama_obat', 'like', "%{$keyword}%");

            });
        }

        return $query;
    }
}

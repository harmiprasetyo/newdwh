<?php

namespace App\Services;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LplpoImport;

class LplpoService
{
  public function import($file, $bulan, $tahun)
{
    Excel::import(new LplpoImport($bulan, $tahun), $file);
}
}

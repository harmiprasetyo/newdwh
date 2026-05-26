<?php
namespace App\Services;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LplpoImport;

use Illuminate\Support\Str;


class LplpoService
{
    public function import($file, $bulan, $tahun)
    {
        // 🔥 simpan file unik
        $fileName = 'import_' . time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs('temp', $fileName);
        $fullPath = storage_path('app/' . $path);

        $import = new \App\Imports\LplpoImport($bulan, $tahun);

        Excel::import($import, $fullPath);

        $errors = $import->getErrors();

        if (!empty($errors)) {

            $errorFile = $import->generateErrorFile($fullPath);

            return [
                'errors' => $errors,
                'file' => $errorFile
            ];
        }

        return ['errors' => []];
    }
}

<?php

namespace App\Imports;

use App\Models\Lplpo\Lplpo;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
class LplpoImport implements ToCollection, WithCalculatedFormulas
{
    protected $bulan;
    protected $tahun;
    protected $errors = [];
    protected $errorCells = [];


    // Label kolom biar user friendly
    private $labels = [
        'nama_obat' => 'Nama Obat',
        'satuan' => 'Satuan',
        'kode_obat' => 'Kode Obat',
    ];

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }


    private function cell($colIndex, $rowIndex)
{
    return \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1)
        . ($rowIndex + 1);
}

    public function getErrors()
    {
        return $this->errors;
    }

     public function getErrorCells()
    {
        return $this->errorCells;
    }

    private function toInt($val)
    {
        if (is_null($val)) return 0;

        if (is_numeric($val)) {
            return (int) round($val);
        }

        $val = str_replace(',', '.', $val);

        return is_numeric($val) ? (int) round($val) : 0;
    }

    // konversi index ke huruf Excel (A, B, C)
    private function colName($index)
    {
        return chr(65 + $index);
    }






private function col($i)
{
    return Coordinate::stringFromColumnIndex($i + 1);
}


/*private function cell($col, $row)
{
    return $this->col($col) . ($row + 1);
}*/



public function collection(Collection $rows)
{
    foreach ($rows as $index => $row) {

        if ($index < 3) continue; // skip header

        if (empty($row[0])) continue;
        $hasError = false;

        try {

            $rowNum = $index + 1;

            // ======================
            // FIELD UTAMA
            // ======================
            $nama_obat = trim($row[1] ?? '');
            $satuan     = trim($row[2] ?? '');
            $kode_obat  = trim($row[3] ?? '');

            // ======================
            // VALIDASI WAJIB
            // ======================

            if ($nama_obat == '') {
                $this->errors[] = "Kesalahan Pada Sel  ".$this->cell(1, $index).": Nama Obat Kosong";

                $this->errorCells[] = [
                    'cell' => $this->cell(1, $index),
                    'message' => 'Nama Obat kosong'
                ];
                  $hasError = true;
            }

            if ($satuan == '') {
                $this->errors[] = "Kesalahan Pada Sel  ".$this->cell(2, $index).$rowNum.": Satuan Kosong";

                $this->errorCells[] = [
                    'cell' => $this->cell(2, $index),
                    'message' => 'Satuan kosong'
                ];
                 $hasError = true;
            }

            if ($kode_obat == '') {
                $this->errors[] = "Kesalahan Pada Sel  ".$this->cell(3, $index).": Kode Obat Kosong";

                $this->errorCells[] = [
                    'cell' => $this->cell(3, $index),
                    'message' => 'Kode obat kosong'
                ];
                  $hasError = true;

            }

            // ======================
            // AMBIL NILAI
            // ======================
            $stok_awal_field1 = $this->toInt($row[4]);
            $stok_awal_field2 = $this->toInt($row[5]);
            $stok_awal_field3 = $this->toInt($row[6]);

            $penerimaan_field1 = $this->toInt($row[7]);
            $penerimaan_field2 = $this->toInt($row[8]);
            $penerimaan_field3 = $this->toInt($row[9]);

            $persediaan_field1 = $this->toInt($row[10]);
            $persediaan_field2 = $this->toInt($row[11]);
            $persediaan_field3 = $this->toInt($row[12]);

            $pemakaian_field1 = $this->toInt($row[13]);
            $pemakaian_field2 = $this->toInt($row[14]);
            $pemakaian_field3 = $this->toInt($row[15]);

            $kadaluarsa = $this->toInt($row[16]);
            $pengembalian = $this->toInt($row[17]);

            $stok_akhir_field1 = $this->toInt($row[18]);
            $stok_akhir_field2 = $this->toInt($row[19]);
            $stok_akhir_field3 = $this->toInt($row[20]);

            // ======================
            // VALIDASI PERSEDIAAN
            // ======================
            if ($stok_awal_field1 + $penerimaan_field1 != $persediaan_field1) {
                $this->errors[] = "Kesalahan Pada Sel  ".$this->cell(10, $index)."/".$this->cell(7, $index)."/".$this->cell(4, $index).": Stok Awal  + Penerimaan  ≠  Persediaan";


                $this->errorCells[] = [
    'cells' => [
        $this->cell(4, $index), // stok awal
        $this->cell(7, $index), // penerimaaan
        $this->cell(10, $index), // persediaan
    ],
    'message' => 'Perhitungan Persediaan tidak valid'
];

                 $hasError = true;
            }

            if ($stok_awal_field2 + $penerimaan_field2 != $persediaan_field2) {
                $this->errors[] = "Kesalahan Pada Sel  ".$this->cell(5, $index)."/".$this->cell(8, $index)."/".$this->cell(11, $index).": Stok Awal  + Penerimaan  ≠  Persediaan";




                 $this->errorCells[] = [
    'cells' => [
        $this->cell(5, $index), // stok awal
        $this->cell(8, $index), // penerimaaan
        $this->cell(11, $index), // persediaan
    ],
    'message' => 'Perhitungan Persediaan tidak valid'
];
                 $hasError = true;
            }

            if ($stok_awal_field3 + $penerimaan_field3 != $persediaan_field3) {
                "Kesalahan Pada Sel  ".$this->cell(6, $index)."/".$this->cell(9, $index)."/".$this->cell(12, $index).": Stok Awal  + Penerimaan  ≠  Persediaan";




                 $this->errorCells[] = [
    'cells' => [
        $this->cell(6, $index), // stok awal
        $this->cell(9, $index), // penerimaaan
        $this->cell(12, $index), // persediaan
    ],
    'message' => 'Perhitungan Persediaan tidak valid'
];

                 $hasError = true;
            }

            // ======================
            // VALIDASI MINUS
            // ======================
            if (($persediaan_field1 - $pemakaian_field1) < 0) {
                $this->errors[] = "Kesalahan Pada Sel  ".$this->cell(10, $index)."/".$this->cell(13, $index)."/". $this->cell(18, $index).": Pemakaian > Persediaan  (Stok Akhir negatif)";

                $this->errorCells[] = [
    'cells' => [
        $this->cell(10, $index), // persediaan
        $this->cell(13, $index), // pemakaian
        $this->cell(18, $index), // stok akhir
    ],
    'message' => 'Perhitungan tidak valid'
];
                $hasError = true;
            }

              if (($persediaan_field2 - $pemakaian_field2) < 0) {
                $this->errors[] = "Kesalahan Pada Sel  ".$this->cell(11, $index)."/".$this->cell(14, $index)."/". $this->cell(19, $index).": Pemakaian > Persediaan  (Stok Akhir negatif)";



                $this->errorCells[] = [
    'cells' => [
        $this->cell(11, $index), // persediaan
        $this->cell(14, $index), // pemakaian
        $this->cell(19, $index), // stok akhir
    ],
    'message' => 'Perhitungan tidak valid'
];
                $hasError = true;
            }


            if (($persediaan_field3 - $pemakaian_field3) < 0) {
               "Kesalahan Pada Sel  ".$this->cell(12, $index)."/".$this->cell(15, $index)."/". $this->cell(20, $index).": Pemakaian > Persediaan  (Stok Akhir negatif)";



                 $this->errorCells[] = [
    'cells' => [
        $this->cell(12, $index), // persediaan
        $this->cell(15, $index), // pemakaian
        $this->cell(20, $index), // stok akhir
    ],
    'message' => 'Perhitungan tidak valid'
];

                 $hasError = true;

            }





            // ======================
            // VALIDASI STOK AKHIR
            // ======================
            $hitung_field1 = $persediaan_field1 - $pemakaian_field1 - $kadaluarsa - $pengembalian;
             $hitung_field2 = $persediaan_field2 - $pemakaian_field2;
              $hitung_field3 = $persediaan_field3 - $pemakaian_field3;

            if ($hitung_field1 != $stok_akhir_field1) {
                $this->errors[] = "Ada Kesalahan pada ".$this->cell(18, $index).": Perhitungan stok akhir salah";

                $this->errorCells[] = [
                    'cell' => $this->cell(18, $index),
                    'message' => 'Perhitungan Stok akhir salah'
                ];

                $this->errorCells[] = [
    'cells' => [
        $this->cell(18, $index), // Stok Akhir
        $this->cell(10, $index), // Persediaan
        $this->cell(13, $index), // Pemakaian
         $this->cell(16, $index),
          $this->cell(17, $index)
    ],
    'message' => 'Perhitungan Stok akhir Tidak valid'
];

                 $hasError = true;

            }




            if ($hitung_field2 != $stok_akhir_field2) {
                $this->errors[] = "Ada Kesalahan pada ".$this->cell(19, $index).": Perhitungan stok akhir salah";

                $this->errorCells[] = [
                    'cell' => $this->cell(19, $index),
                    'message' => 'Stok akhir salah'
                ];

                $this->errorCells[] = [
    'cells' => [
        $this->cell(19, $index), // Stok Akhir
        $this->cell(11, $index), // Persediaan
        $this->cell(14, $index), // Pemakaian
    ],
    'message' => 'Perhitungan Stok akhir Tidak valid'
];

                 $hasError = true;

            }

              if ($hitung_field3 != $stok_akhir_field3) {
                $this->errors[] = "Ada Kesalahan pada ".$this->cell(20, $index).": Perhitungan stok akhir salah";

                $this->errorCells[] = [
                    'cell' => $this->cell(20, $index),
                    'message' => 'Stok akhir salah'
                ];

                $this->errorCells[] = [
    'cells' => [
        $this->cell(20, $index), // Stok Akhir
        $this->cell(12, $index), // Persediaan
        $this->cell(15, $index), // Pemakaian
    ],
    'message' => 'Perhitungan Stok akhir Tidak valid'
];

                 $hasError = true;

            }


             if ($hasError) {
                continue;
                }

            // ======================
            // SIMPAN
            // ======================
            Lplpo::updateOrCreate(
                [
                    'kode_faskes' => auth()->user()->kodeFaskes ?? 'DEFAULT',
                    'bulan' => $this->bulan,
                    'tahun' => $this->tahun,
                    'kode_obat' => $kode_obat,
                ],
                [
                    'nama_obat' => $nama_obat,
                    'satuan' => $satuan,
                    'stok_awal_field1' => $stok_awal_field1,
                    'stok_awal_field2' => $stok_awal_field2,
                    'stok_awal_field3' => $stok_awal_field3,
                    'penerimaan_field1' => $penerimaan_field1,
                    'penerimaan_field2' => $penerimaan_field2,
                    'penerimaan_field3' => $penerimaan_field3,
                    'persediaan_field1' => $persediaan_field1,
                    'persediaan_field2' => $persediaan_field2,
                    'persediaan_field3' => $persediaan_field3,
                    'pemakaian_field1' => $pemakaian_field1,
                    'pemakaian_field2' => $pemakaian_field2,
                    'pemakaian_field3' => $pemakaian_field3,
                    'kadaluarsa' => $kadaluarsa,
                    'pengembalian' => $pengembalian,
                    'stok_akhir_field1' => $stok_akhir_field1,
                    'stok_akhir_field2' => $stok_akhir_field2,
                    'stok_akhir_field3' => $stok_akhir_field3,
                    'rko' => $this->toInt($row[21]),
                    'stok_optimum' => $this->toInt($row[22]),
                    'permintaan' => $this->toInt($row[23]),
                    'keterangan' => $row[25] ?? ''
                ]
            );

        } catch (\Exception $e) {

            $this->errors[] = "Baris  ".$this->cell(1, $index)." error: ".$e->getMessage();

            $this->errorCells[] = [
                'cell' => $this->cell(1, $index),
                'message' => 'Error sistem'
            ];
        }

        if (count($this->errors) > 50) break;
    }
}

  public function generateErrorFile($filePath)
{

    //dd($this->errorCells);
$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($filePath);
    $spreadsheet = $reader->load($filePath);
    $sheet = $spreadsheet->getActiveSheet();


    $processedRows = [];

foreach ($this->errorCells as $err) {

    $cells = $err['cells'] ?? [$err['cell']];
    $row = preg_replace('/[^0-9]/', '', $cells[0]);

    // ✅ 1. highlight baris hanya sekali
    if (!isset($processedRows[$row])) {

        $sheet->getStyle("A{$row}:Z{$row}")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFCCCC');

        $processedRows[$row] = true;
    }

    // ✅ 2. highlight SEMUA cell
    foreach ($cells as $cell) {

        $sheet->getStyle($cell)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFF0000');

        $sheet->getComment($cell)
            ->getText()->createTextRun($err['message']);
    }

    // ✅ 3. gabung error (tidak overwrite)
    $existing = $sheet->getCell('Z'.$row)->getValue();

    $sheet->setCellValue(
        'Z'.$row,
        trim(($existing ? $existing."\n" : '').$err['message'])
    );
}





    $ext = pathinfo($filePath, PATHINFO_EXTENSION);

    $fileName = 'lplpo_error_' . time() . '.' . $ext;
    $path = storage_path('app/public/' . $fileName);

    $writerType = ucfirst(strtolower($ext));
    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, $writerType);

    $writer->save($path);

    $spreadsheet->disconnectWorksheets();
    unset($spreadsheet);

    return 'storage/' . $fileName;
}
}

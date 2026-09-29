<?php

namespace App\Exports\NewLplpo;

use App\Models\NewLplpo\Item;
use App\Models\NewLplpo\Report;
use App\Models\NewLplpo\StokMinimalObat;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LplpoDetailExport implements FromArray, WithEvents
{
    protected Report $report;

    protected Collection $items;

    protected array $rows = [];

    protected array $napzaRows = [];

    protected array $programRows = [];

    protected int $itemNumber = 0;

    protected int $headerRow = 8;

    protected const TOTAL_COLUMNS = 20;

    public function __construct(Report $report)
    {
        $this->report = $report;

        $this->loadItems();

        $this->buildRows();
    }

    /**
     * ==========================================================
     * LOAD ITEMS
     * ==========================================================
     */
    protected function loadItems(): void
    {
        $items = Item::with([
                'program',
                'obat',
            ])
            ->where('report_id', $this->report->id)
            ->orderByRaw("
                CASE
                    WHEN program_id = 1 THEN 0
                    WHEN LOWER(TRIM(program_name)) = 'non program' THEN 0
                    ELSE 1
                END
            ")
            ->orderBy('program_id')
            ->orderBy('nama_obat')
            ->get();

        $kodeObat = $items
            ->pluck('kode_obat')
            ->filter()
            ->unique()
            ->values();

        $stokMinimal = collect();

        if ($kodeObat->isNotEmpty()) {
            $stokMinimal = StokMinimalObat::query()
                ->where('kodeFaskes', $this->report->kode_faskes)
                ->where('tahun', $this->report->tahun)
                ->whereIn('kode_obat', $kodeObat)
                ->get()
                ->keyBy('kode_obat');
        }

        $items->each(function ($item) use ($stokMinimal) {

            $stok = $stokMinimal->get($item->kode_obat);

            $item->obat_esensial =
                optional($stok)->obat_esensial;

            $item->obat_formularium_puskesmas =
                optional($stok)->obat_formularium_puskesmas;

            $item->obat_napza =
                optional($item->obat)->obat_napza;
        });

        $this->items = $items;
    }

    /**
     * ==========================================================
     * BUILD ROWS
     * ==========================================================
     */
    protected function buildRows(): void
    {
        /*
        |--------------------------------------------------------------------------
        | ROW 1 - TITLE
        |--------------------------------------------------------------------------
        */

        $this->rows[] = $this->makeRow([
            'LPLPO - LAPORAN PEMAKAIAN DAN LEMBAR PERMINTAAN OBAT',
        ]);

        /*
        |--------------------------------------------------------------------------
        | ROW 2 - NOMOR
        |--------------------------------------------------------------------------
        */

        $this->rows[] = $this->makeRow([
            'Nomor LPLPO',
            $this->cleanValue($this->report->nomor_lplpo),
        ]);

        /*
        |--------------------------------------------------------------------------
        | ROW 3 - FASKES
        |--------------------------------------------------------------------------
        */

        $this->rows[] = $this->makeRow([
            'Fasilitas Kesehatan',
            $this->cleanValue($this->report->nama_faskes),
        ]);

        /*
        |--------------------------------------------------------------------------
        | ROW 4 - PERIODE
        |--------------------------------------------------------------------------
        */

        $this->rows[] = $this->makeRow([
            'Periode',
            sprintf(
                '%02d/%d',
                $this->report->bulan,
                $this->report->tahun
            ),
        ]);

        /*
        |--------------------------------------------------------------------------
        | ROW 5 - SPACING
        |--------------------------------------------------------------------------
        */

        $this->rows[] = $this->makeRow();

        /*
        |--------------------------------------------------------------------------
        | ROW 6 - LEGEND
        |--------------------------------------------------------------------------
        */

        $this->rows[] = $this->makeRow([
            'Keterangan',
            'OE = Obat Esensial',
            'NOE = Non Obat Esensial',
            'Ya = Formularium PKM',
            'NAPZA = Obat NAPZA',
        ]);

        /*
        |--------------------------------------------------------------------------
        | ROW 7 - SPACING
        |--------------------------------------------------------------------------
        */

        $this->rows[] = $this->makeRow();

        /*
        |--------------------------------------------------------------------------
        | ROW 8 - TABLE HEADER
        |--------------------------------------------------------------------------
        */

        $this->rows[] = $this->headings();

        /*
        |--------------------------------------------------------------------------
        | ITEMS
        |--------------------------------------------------------------------------
        */

        $lastProgramId = null;
        $lastProgramName = null;

        foreach ($this->items as $item) {

            $programName = trim(
                (string) (
                    $item->program_name
                    ?? optional($item->program)->program_name
                    ?? 'Non Program'
                )
            );

            if ($programName === '') {
                $programName = 'Non Program';
            }

            $isNonProgram =
                (int) $item->program_id === 1
                ||
                strtolower($programName) === 'non program';

            /*
            |--------------------------------------------------------------------------
            | PROGRAM SEPARATOR
            |--------------------------------------------------------------------------
            */

            if (!$isNonProgram) {

                $programChanged =
                    $lastProgramId !== $item->program_id
                    ||
                    $lastProgramName !== $programName;

                if ($programChanged) {

                    /*
                    | Semua 20 cell tetap dibuat.
                    | Nanti A:T akan di-merge oleh AfterSheet.
                    */
                    $this->rows[] = $this->makeRow([
                        $programName,
                    ]);

                    $this->programRows[] = count($this->rows);

                    $lastProgramId = $item->program_id;
                    $lastProgramName = $programName;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | ITEM NUMBER
            |--------------------------------------------------------------------------
            */

            $this->itemNumber++;

            $isNapza = strtolower(
                trim((string) ($item->obat_napza ?? 'tidak'))
            ) === 'ya';

            /*
            |--------------------------------------------------------------------------
            | NAPZA ROW
            |--------------------------------------------------------------------------
            */

            if ($isNapza) {
                $this->napzaRows[] = count($this->rows) + 1;
            }

            /*
            |--------------------------------------------------------------------------
            | ITEM
            |--------------------------------------------------------------------------
            */

            $this->rows[] = $this->mapItem(
                $item,
                $this->itemNumber,
                $isNapza
            );
        }
    }

    /**
     * ==========================================================
     * MAKE EXACTLY 20 COLUMNS
     * ==========================================================
     */
    protected function makeRow(array $values = []): array
    {
        $values = array_values($values);

        return array_pad(
            array_slice(
                $values,
                0,
                self::TOTAL_COLUMNS
            ),
            self::TOTAL_COLUMNS,
            ''
        );
    }

    /**
     * ==========================================================
     * HEADINGS
     * ==========================================================
     */
    protected function headings(): array
    {
        return [
            'No',
            'Kode',
            'Nama Obat',
            'Sat',
            'Esensial',
            'Formularium PKM',

            'Stok Awal PKD',
            'Stok Awal JKN',

            'Penerimaan PKD',
            'Penerimaan JKN',

            'Persediaan PKD',
            'Persediaan JKN',

            'Pemakaian PKD',
            'Pemakaian JKN',

            'Expired PKD',
            'Expired JKN',

            'Stok Akhir PKD',
            'Stok Akhir JKN',

            'Permintaan',
            'Pemberian',
        ];
    }

    /**
     * ==========================================================
     * MAP ITEM
     * ==========================================================
     */
    protected function mapItem(
        $item,
        int $number,
        bool $isNapza
    ): array {
        $namaObat = $this->cleanValue($item->nama_obat);

        if ($isNapza) {
            $namaObat .= ' [NAPZA]';
        }

        return [
            $number,

            $this->cleanValue($item->kode_obat),

            $namaObat,

            $this->cleanValue($item->satuan),

            strtoupper(
                $this->cleanValue(
                    $item->obat_esensial ?: 'NOE'
                )
            ),

            strtolower(
                trim(
                    (string) $item->obat_formularium_puskesmas
                )
            ) === 'true'
                ? 'Ya'
                : 'Tidak',

            (int) $item->stok_awal_program_pkd,
            (int) $item->stok_awal_jkn,

            (int) $item->penerimaan_program_pkd,
            (int) $item->penerimaan_jkn,

            (int) $item->persediaan_program_pkd,
            (int) $item->persediaan_jkn,

            (int) $item->pemakaian_program_pkd,
            (int) $item->pemakaian_jkn,

            (int) $item->expired_program_pkd,
            (int) $item->expired_jkn,

            (int) $item->stok_akhir_program_pkd,
            (int) $item->stok_akhir_jkn,

            (int) $item->permintaan,

            (int) $item->pemberian_program_pkd
                + (int) $item->pemberian_jkn,
        ];
    }

    /**
     * ==========================================================
     * CLEAN VALUE
     * ==========================================================
     */
    protected function cleanValue($value): string
    {
        if ($value === null) {
            return '';
        }

        return trim(
            preg_replace(
                '/[\x00-\x1F\x7F\x{00A0}]+/u',
                ' ',
                (string) $value
            )
        );
    }

    /**
     * ==========================================================
     * ARRAY
     * ==========================================================
     */
    public function array(): array
    {
        return $this->rows;
    }

    /**
     * ==========================================================
     * EVENTS
     * ==========================================================
     */
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                $lastColumn = 'T';
                $lastRow = $sheet->getHighestRow();

                /*
                |--------------------------------------------------------------------------
                | TITLE
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells('A1:T1');

                $sheet
                    ->getStyle('A1:T1')
                    ->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'size' => 14,
                        ],

                        'alignment' => [
                            'horizontal' =>
                                Alignment::HORIZONTAL_CENTER,

                            'vertical' =>
                                Alignment::VERTICAL_CENTER,
                        ],
                    ]);

                $sheet
                    ->getRowDimension(1)
                    ->setRowHeight(28);

                /*
                |--------------------------------------------------------------------------
                | REPORT INFORMATION
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getStyle('A2:B4')
                    ->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ],
                    ]);

                /*
                |--------------------------------------------------------------------------
                | LEGEND
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getStyle('A6:E6')
                    ->applyFromArray([
                        'font' => [
                            'italic' => true,
                        ],

                        'alignment' => [
                            'vertical' =>
                                Alignment::VERTICAL_CENTER,
                        ],
                    ]);

                /*
                |--------------------------------------------------------------------------
                | TABLE HEADER
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getStyle("A{$this->headerRow}:T{$this->headerRow}")
                    ->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => [
                                'rgb' => 'FFFFFF',
                            ],
                        ],

                        'fill' => [
                            'fillType' =>
                                Fill::FILL_SOLID,

                            'startColor' => [
                                'rgb' => '4472C4',
                            ],
                        ],

                        'alignment' => [
                            'horizontal' =>
                                Alignment::HORIZONTAL_CENTER,

                            'vertical' =>
                                Alignment::VERTICAL_CENTER,

                            'wrapText' => true,
                        ],

                        'borders' => [
                            'allBorders' => [
                                'borderStyle' =>
                                    Border::BORDER_THIN,
                            ],
                        ],
                    ]);

                $sheet
                    ->getRowDimension($this->headerRow)
                    ->setRowHeight(40);

                /*
                |--------------------------------------------------------------------------
                | PROGRAM SEPARATOR
                |--------------------------------------------------------------------------
                */

             foreach ($this->programRows as $row) {

    $sheet->mergeCells("A{$row}:T{$row}");

    $sheet
        ->getStyle("A{$row}:T{$row}")
        ->applyFromArray([
            'font' => [
                'bold' => true,
            ],

            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'CFE2FF',
                ],
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'indent' => 1,
            ],

            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

    /*
    |--------------------------------------------------------------------------
    | Pastikan cell utama hasil merge A:T rata kiri
    |--------------------------------------------------------------------------
    */

    $sheet
        ->getStyle("A{$row}")
        ->getAlignment()
        ->setHorizontal(
            Alignment::HORIZONTAL_LEFT
        )
        ->setVertical(
            Alignment::VERTICAL_CENTER
        )
        ->setIndent(1);
}
                /*
                |--------------------------------------------------------------------------
                | DATA BORDER
                |--------------------------------------------------------------------------
                */

                if ($lastRow >= $this->headerRow) {

                    $sheet
                        ->getStyle(
                            "A{$this->headerRow}:T{$lastRow}"
                        )
                        ->getBorders()
                        ->getAllBorders()
                        ->setBorderStyle(
                            Border::BORDER_THIN
                        );
                }

                /*
                |--------------------------------------------------------------------------
                | NAPZA
                |--------------------------------------------------------------------------
                */

                foreach ($this->napzaRows as $row) {

                    $sheet
                        ->getStyle("A{$row}:T{$row}")
                        ->applyFromArray([
                            'fill' => [
                                'fillType' =>
                                    Fill::FILL_SOLID,

                                'startColor' => [
                                    'rgb' => 'F8D7DA',
                                ],
                            ],
                        ]);
                }

                /*
                |--------------------------------------------------------------------------
                | OE / NOE / FORMULARIUM
                |--------------------------------------------------------------------------
                */

                for (
                    $row = $this->headerRow + 1;
                    $row <= $lastRow;
                    $row++
                ) {

                    if (
                        in_array(
                            $row,
                            $this->programRows,
                            true
                        )
                    ) {
                        continue;
                    }

                    $essential = strtoupper(
                        (string) $sheet
                            ->getCell("E{$row}")
                            ->getValue()
                    );

                    if ($essential === 'OE') {

                        $sheet
                            ->getStyle("E{$row}")
                            ->applyFromArray([
                                'font' => [
                                    'bold' => true,
                                    'color' => [
                                        'rgb' => '198754',
                                    ],
                                ],
                            ]);
                    }

                    if ($essential === 'NOE') {

                        $sheet
                            ->getStyle("E{$row}")
                            ->applyFromArray([
                                'font' => [
                                    'bold' => true,
                                    'color' => [
                                        'rgb' => '6C757D',
                                    ],
                                ],
                            ]);
                    }

                    $formularium = strtolower(
                        trim(
                            (string) $sheet
                                ->getCell("F{$row}")
                                ->getValue()
                        )
                    );

                    if ($formularium === 'ya') {

                        $sheet
                            ->getStyle("F{$row}")
                            ->applyFromArray([
                                'font' => [
                                    'bold' => true,
                                    'color' => [
                                        'rgb' => '0D6EFD',
                                    ],
                                ],
                            ]);
                    }
                }

               /*
|--------------------------------------------------------------------------
| ALIGNMENT
|--------------------------------------------------------------------------
*/

if ($lastRow >= 9) {

    $sheet
        ->getStyle("A9:B{$lastRow}")
        ->getAlignment()
        ->setHorizontal(
            Alignment::HORIZONTAL_CENTER
        );

    $sheet
        ->getStyle("D9:F{$lastRow}")
        ->getAlignment()
        ->setHorizontal(
            Alignment::HORIZONTAL_CENTER
        );

    $sheet
        ->getStyle("G9:T{$lastRow}")
        ->getAlignment()
        ->setHorizontal(
            Alignment::HORIZONTAL_RIGHT
        );

    /*
    |--------------------------------------------------------------------------
    | PROGRAM SEPARATOR HARUS RATA KIRI
    |--------------------------------------------------------------------------
    */

    foreach ($this->programRows as $row) {

        $sheet
            ->getStyle("A{$row}")
            ->getAlignment()
            ->setHorizontal(
                Alignment::HORIZONTAL_LEFT
            )
            ->setVertical(
                Alignment::VERTICAL_CENTER
            )
            ->setIndent(0);
    }
}

                /*
                |--------------------------------------------------------------------------
                | NUMBER FORMAT
                |--------------------------------------------------------------------------
                */

                if ($lastRow >= 9) {

                    $sheet
                        ->getStyle("G9:T{$lastRow}")
                        ->getNumberFormat()
                        ->setFormatCode('#,##0');
                }

                /*
                |--------------------------------------------------------------------------
                | FREEZE
                |--------------------------------------------------------------------------
                */

                $sheet->freezePane('A9');

                /*
                |--------------------------------------------------------------------------
                | AUTO FILTER
                |--------------------------------------------------------------------------
                */

                $sheet->setAutoFilter(
                    "A{$this->headerRow}:T{$lastRow}"
                );

                /*
                |--------------------------------------------------------------------------
                | COLUMN WIDTH
                |--------------------------------------------------------------------------
                */

                foreach (range(1, self::TOTAL_COLUMNS) as $column) {

                    $letter =
                        Coordinate::stringFromColumnIndex(
                            $column
                        );

                    $sheet
                        ->getColumnDimension($letter)
                        ->setAutoSize(true);
                }

                /*
                |--------------------------------------------------------------------------
                | MANUAL WIDTH
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getColumnDimension('A')
                    ->setWidth(7);

                $sheet
                    ->getColumnDimension('B')
                    ->setWidth(16);

                $sheet
                    ->getColumnDimension('C')
                    ->setWidth(35);

                $sheet
                    ->getColumnDimension('D')
                    ->setWidth(12);

                /*
                |--------------------------------------------------------------------------
                | TAB COLOR
                |--------------------------------------------------------------------------
                */

                $sheet
                    ->getTabColor()
                    ->setRGB('4472C4');
            },
        ];
    }
}

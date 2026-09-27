<?php

namespace App\Exports\NewLplpo;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class LplpoRekapExport implements FromArray, WithEvents
{
    protected Collection $items;

    protected int $bulanMulai;
    protected int $tahunMulai;
    protected int $bulanSampai;
    protected int $tahunSampai;

    protected array $programRows = [];
    protected array $napzaRows = [];

    protected array $rows = [];

    protected int $headerRow = 6;

    protected const TOTAL_COLUMNS = 20;

    public function __construct(
        Collection $items,
        int $bulanMulai,
        int $tahunMulai,
        int $bulanSampai,
        int $tahunSampai
    ) {
        $this->items = $items;

        $this->bulanMulai = $bulanMulai;
        $this->tahunMulai = $tahunMulai;
        $this->bulanSampai = $bulanSampai;
        $this->tahunSampai = $tahunSampai;

        $this->buildRows();
    }

    protected function buildRows(): void
    {
        $this->rows[] = $this->makeRow([
            'REKAPITULASI LPLPO'
        ]);

        $this->rows[] = $this->makeRow([
            'Periode',
            sprintf(
                '%02d/%04d - %02d/%04d',
                $this->bulanMulai,
                $this->tahunMulai,
                $this->bulanSampai,
                $this->tahunSampai
            )
        ]);

        $this->rows[] = $this->makeRow();

        $this->rows[] = $this->makeRow([
            'Keterangan',
            'OE = Obat Esensial',
            'NOE = Non Obat Esensial',
            'Ya = Formularium PKM',
            'NAPZA = Obat NAPZA'
        ]);

        $this->rows[] = $this->makeRow();

        $this->rows[] = $this->headings();

        $lastProgramId = null;
        $lastProgramName = null;

        foreach ($this->items as $item) {

            $programName = trim(
                (string) (
                    $item->program_name
                    ?? 'Non Program'
                )
            );

            if ($programName === '') {
                $programName = 'Non Program';
            }

            $isNonProgram =
                (int) $item->program_id === 1 ||
                strtolower($programName) === 'non program';

            /*
            |--------------------------------------------------------------------------
            | NON PROGRAM TIDAK DIBUAT SEPARATOR
            |--------------------------------------------------------------------------
            */

            if (!$isNonProgram) {

                $programChanged =
                    $lastProgramId !== $item->program_id ||
                    $lastProgramName !== $programName;

                if ($programChanged) {

                    $this->rows[] =
                        $this->makeRow([
                            $programName
                        ]);

                    $this->programRows[] =
                        count($this->rows);

                    $lastProgramId =
                        $item->program_id;

                    $lastProgramName =
                        $programName;
                }
            }

            $isNapza =
                strtolower(
                    trim(
                        (string) $item->obat_napza
                    )
                ) === 'ya';

            $this->rows[] = [

                ++$this->itemNumber,

                $item->kode_obat,

                $item->nama_obat,

                $item->satuan,

                strtoupper(
                    $item->obat_esensial ?: 'NOE'
                ),

                strtolower(
                    (string)
                    $item->obat_formularium_puskesmas
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

                (int) $item->item_expired_pkd,
                (int) $item->item_expired_jkn,

                (int) $item->stok_akhir_program_pkd,
                (int) $item->stok_akhir_jkn,

                (int) $item->permintaan,

                /*
                |--------------------------------------------------------------------------
                | PEMBERIAN = PKD + JKN
                |--------------------------------------------------------------------------
                */

                (int) $item->pemberian_program_pkd +
                (int) $item->pemberian_jkn,
            ];

            if ($isNapza) {
                $this->napzaRows[] =
                    count($this->rows);
            }
        }
    }

    protected int $itemNumber = 0;

    protected function makeRow(array $values = []): array
    {
        return array_pad(
            array_slice(
                array_values($values),
                0,
                self::TOTAL_COLUMNS
            ),
            self::TOTAL_COLUMNS,
            ''
        );
    }

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

    public function array(): array
    {
        return $this->rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (
                AfterSheet $event
            ) {

                $sheet =
                    $event->sheet->getDelegate();

                $lastRow =
                    $sheet->getHighestRow();

                /*
                |--------------------------------------------------------------------------
                | TITLE
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells(
                    'A1:T1'
                );

                $sheet->getStyle(
                    'A1:T1'
                )->applyFromArray([

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

                /*
                |--------------------------------------------------------------------------
                | HEADER
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle(
                    "A{$this->headerRow}:T{$this->headerRow}"
                )->applyFromArray([

                    'font' => [
                        'bold' => true,
                        'color' => [
                            'rgb' => 'FFFFFF'
                        ],
                    ],

                    'fill' => [
                        'fillType' =>
                            Fill::FILL_SOLID,

                        'startColor' => [
                            'rgb' => '198754'
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
                                Border::BORDER_THIN
                        ]
                    ],
                ]);

                /*
                |--------------------------------------------------------------------------
                | BORDER
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle(
                    "A{$this->headerRow}:T{$lastRow}"
                )
                ->getBorders()
                ->getAllBorders()
                ->setBorderStyle(
                    Border::BORDER_THIN
                );

                /*
                |--------------------------------------------------------------------------
                | PROGRAM SEPARATOR
                |--------------------------------------------------------------------------
                */

                foreach (
                    $this->programRows
                    as $row
                ) {

                    $sheet->mergeCells(
                        "A{$row}:T{$row}"
                    );

                    $sheet->getStyle(
                        "A{$row}:T{$row}"
                    )->applyFromArray([

                        'font' => [
                            'bold' => true
                        ],

                        'fill' => [
                            'fillType' =>
                                Fill::FILL_SOLID,

                            'startColor' => [
                                'rgb' => 'CFE2FF'
                            ],
                        ],
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | WAJIB RATA KIRI
                    |--------------------------------------------------------------------------
                    */

                    $sheet->getStyle(
                        "A{$row}"
                    )->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_LEFT
                        )
                        ->setVertical(
                            Alignment::VERTICAL_CENTER
                        )
                        ->setIndent(0);
                }

                /*
                |--------------------------------------------------------------------------
                | NAPZA
                |--------------------------------------------------------------------------
                */

                foreach (
                    $this->napzaRows
                    as $row
                ) {

                    $sheet->getStyle(
                        "A{$row}:T{$row}"
                    )->applyFromArray([

                        'fill' => [
                            'fillType' =>
                                Fill::FILL_SOLID,

                            'startColor' => [
                                'rgb' => 'F8D7DA'
                            ],
                        ],
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | ALIGNMENT
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle(
                    "A{$this->headerRow}:B{$lastRow}"
                )->getAlignment()
                    ->setHorizontal(
                        Alignment::HORIZONTAL_CENTER
                    );

                $sheet->getStyle(
                    "D{$this->headerRow}:F{$lastRow}"
                )->getAlignment()
                    ->setHorizontal(
                        Alignment::HORIZONTAL_CENTER
                    );

                $sheet->getStyle(
                    "G{$this->headerRow}:T{$lastRow}"
                )->getAlignment()
                    ->setHorizontal(
                        Alignment::HORIZONTAL_RIGHT
                    );

                /*
                |--------------------------------------------------------------------------
                | REAPPLY PROGRAM LEFT
                |--------------------------------------------------------------------------
                */

                foreach (
                    $this->programRows
                    as $row
                ) {

                    $sheet->getStyle(
                        "A{$row}"
                    )->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_LEFT
                        )
                        ->setIndent(0);
                }

                /*
                |--------------------------------------------------------------------------
                | NUMBER
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle(
                    "G{$this->headerRow}:T{$lastRow}"
                )
                ->getNumberFormat()
                ->setFormatCode('#,##0');

                /*
                |--------------------------------------------------------------------------
                | WIDTH
                |--------------------------------------------------------------------------
                */

                foreach (
                    range(
                        1,
                        self::TOTAL_COLUMNS
                    ) as $column
                ) {

                    $letter =
                        Coordinate::stringFromColumnIndex(
                            $column
                        );

                    $sheet
                        ->getColumnDimension($letter)
                        ->setAutoSize(true);
                }

                $sheet
                    ->getColumnDimension('A')
                    ->setWidth(7);

                $sheet
                    ->getColumnDimension('B')
                    ->setWidth(16);

                $sheet
                    ->getColumnDimension('C')
                    ->setWidth(35);

                $sheet->freezePane(
                    'A7'
                );

                $sheet->setAutoFilter(
                    "A{$this->headerRow}:T{$lastRow}"
                );
            },
        ];
    }
}

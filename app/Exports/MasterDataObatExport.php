<?php
namespace App\Exports;

use App\Models\NewLplpo\MasterDataObat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MasterDataObatExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return MasterDataObat::query()
            ->orderBy('nama_obat')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Obat',
            'Nama Obat',
            'Satuan',
            'Obat-obatan Napza',
        ];
    }

    public function map($obat): array
    {
        static $no = 0;

        $no++;

        return [
            $no,
            $obat->kode_obat,
            $obat->nama_obat,
            $obat->satuan,
            $obat->obat_napza ? 'Ya' : 'Tidak',
        ];
    }
}

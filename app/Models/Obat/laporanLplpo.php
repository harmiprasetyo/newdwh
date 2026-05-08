<?php

namespace App\Models\Obat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Obat\master_obat;
use App\Models\Master\MasterFaskes;


class laporanLplpo extends Model
{
    use HasFactory;
     protected $table = 'data_lplpo';

    protected $fillable = [
        'kode','bulan','tahun',
        'stok_awal_pkd','stok_awal_program',
        'penerimaan_pkd','penerimaan_program',
        'persediaan_pkd','persediaan_program',
        'pemakaian_pkd','pemakaian_program',
        'kadaluarsa','pengembalian',
        'stok_akhir_pkd','stok_akhir_program',
        'rko','stok_optimum','permintaan','pemberian',
        'keterangan'
    ];

    public function obat()
    {
        return $this->belongsTo(master_obat::class, 'kode', 'kode_obat');
    }

     public function faskes()
    {
        return $this->belongsTo(MasterFaskes::class, 'kodeFaskes', 'kode_faskes');
    }
}

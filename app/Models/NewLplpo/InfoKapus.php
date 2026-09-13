<?php

namespace App\Models\NewLplpo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class InfoKapus extends Model
{
    use HasFactory;

    protected $table = 'info_kapus';

    protected $fillable = [
        'kodeFaskes',
        'namaKapus',
        'nipKapus',
        'emailKapus',
        'kodeEsign',
    ];

    protected $hidden = [
        'kodeEsign',
    ];

    /**
     * Relasi ke link approval.
     */
    public function linkApprovals()
    {
        return $this->hasMany(
            InfoLinkApproval::class,
            'kapusId',
            'id'
        );
    }

    /**
     * Generate kode E-Sign 8 digit.
     */
    public static function generateEsign(): string
    {
        return (string) random_int(
            10000000,
            99999999
        );
    }

    /**
     * Simpan E-Sign dalam bentuk HASH.
     */
    public function setEsign(string $kode): void
    {
        $this->kodeEsign = Hash::make($kode);
    }

    /**
     * Validasi E-Sign.
     */
    public function checkEsign(string $kode): bool
    {
        if (empty($this->kodeEsign)) {
            return false;
        }

        return Hash::check(
            $kode,
            $this->kodeEsign
        );
    }
}
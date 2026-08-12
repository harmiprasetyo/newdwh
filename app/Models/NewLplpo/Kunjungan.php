<?php

namespace App\Models\NewLplpo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kunjungan extends Model
{
    protected $table = 'new_lplpo_kunjungan';

    protected $fillable = [
        'report_id',

        'kunjungan_jkn',
        'kunjungan_tunai',
        'kunjungan_gratis',
        'total_kunjungan_perkategori',

        'kunjungan_anak',
        'kunjungan_dewasa',
        'total_kunjungan_pergender',

        'kunjungan_lab',
        'kunjungan_gigi',
        'kunjungan_poned',
        'kunjungan_rawatinap',
        'kunjungan_rawatjalan',
    ];

    protected $casts = [
        'report_id' => 'integer',

        'kunjungan_jkn' => 'integer',
        'kunjungan_tunai' => 'integer',
        'kunjungan_gratis' => 'integer',
        'total_kunjungan_perkategori' => 'integer',

        'kunjungan_anak' => 'integer',
        'kunjungan_dewasa' => 'integer',
        'total_kunjungan_pergender' => 'integer',

        'kunjungan_lab' => 'integer',
        'kunjungan_gigi' => 'integer',
        'kunjungan_poned' => 'integer',
        'kunjungan_rawatinap' => 'integer',
        'kunjungan_rawatjalan' => 'integer',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(
            Report::class,
            'report_id',
            'id'
        );
    }
}

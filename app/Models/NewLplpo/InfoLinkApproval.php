<?php

namespace App\Models\NewLplpo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InfoLinkApproval extends Model
{
    use HasFactory;

    protected $table = 'info_linkapproval';

    protected $fillable = [
        'reportId',
        'kapusId',
        'approvalToken',
        'verificationToken',
        'status',
        'approvedBy',
        'approvedAt',
        'rejectedReason',
        'rejectedAt',
    ];

    protected $casts = [
        'approvedAt' => 'datetime',
        'rejectedAt' => 'datetime',
    ];

    /**
     * Relasi ke laporan LPLPO.
     */
    public function report()
    {
        return $this->belongsTo(
            Report::class,
            'reportId',
            'id'
        );
    }

    /**
     * Relasi ke Kapus.
     */
    public function kapus()
    {
        return $this->belongsTo(
            InfoKapus::class,
            'kapusId',
            'id'
        );
    }
}

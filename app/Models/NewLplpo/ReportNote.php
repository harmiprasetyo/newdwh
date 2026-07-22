<?php

namespace App\Models\NewLplpo;

use Illuminate\Database\Eloquent\Model;

class ReportNote extends Model
{
    protected $table='new_lplpo_report_notes';

    protected $fillable=[
        'report_id',
        'note_type',
        'note',
        'created_by'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}

<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabelLplpo extends Model
{
    use HasFactory;
    protected $table = "label_lplpo";
    protected $fillable = [
        "kodeKab",
        "field1","field2","field3"
    ];
}

<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravolt\Indonesia\Models\City;

class LabelLplpo extends Model
{
    use HasFactory;
    protected $table = "label_lplpo";
    protected $fillable = [
        "kodeKab",
        "field1","field2","field3"
    ];

    // LabelLplpo.php
public function kabupaten()
{
    return $this->belongsTo(City::class, 'kodeKab', 'code');
}


}

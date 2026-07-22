<?php

namespace App\Models\NewLplpo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Program extends Model
{
    use HasFactory;

    protected $table='new_lplpo_program_list';

    protected $fillable=[
        'program_name'
    ];

    public $timestamps=false;

    public function items()
    {
        return $this->hasMany(Item::class,'program_id');
    }

}

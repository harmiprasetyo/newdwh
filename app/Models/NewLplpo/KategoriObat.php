<?php
namespace App\Models\NewLplpo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriObat extends Model
{
    use HasFactory;

    protected $table = 'new_lplpo_kategori';

    protected $fillable = [
        'kategori',
    ];
}


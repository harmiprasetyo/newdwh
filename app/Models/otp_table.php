<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class otp_table extends Model
{
    use HasFactory;
     protected $fillable = [
        'identifier',
        'otp',
        'expires_at',
        'attempts',
        'is_used'
    ];
}

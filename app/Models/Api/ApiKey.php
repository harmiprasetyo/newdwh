<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    protected $fillable = [
        'tenant_id', 'key', 'name', 'is_active'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}

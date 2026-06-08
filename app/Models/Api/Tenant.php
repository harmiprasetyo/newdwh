<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Api\ApiKey;

class Tenant extends Model
{
    protected $fillable = [
        'name', 'code', 'environment', 'ip_whitelist'
    ];

    protected $casts = [
        'ip_whitelist' => 'array'
    ];

    public function apiKeys()
    {
        return $this->hasMany(ApiKey::class);
    }
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

use Laravel\Sanctum\HasApiTokens;
class UsersApp extends Authenticatable
{
use HasApiTokens;
protected $table = 'users_app';
    protected $primaryKey = 'userid';
    public $incrementing = false;
    protected $keyType = 'string';




    protected $fillable = [
        'userid',
    'username',
    'groupid',
    'email',
    'namalengkap',
    'kodeFaskes',
    'namaFaskes',
    'kodePropinsi',
    'kodeKota',
    'kodeKecamatan',
    'password',
    'api_token'
    ];

      protected $hidden = ['password'];

    // 🔥 LOGIN pakai username (bukan email)
    public function getAuthIdentifierName()
    {
        return 'username';
    }


public function isDinkes()
{
    return $this->role === 'dinkes';
}

public function isFaskes()
{
    return $this->role === 'faskes';
}
    public function group()
    {
        return $this->belongsTo(\App\Models\UserGroups::class, 'groupid', 'group_id');
    }

    public function provinsi()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\Province::class, 'kodePropinsi', 'code');
    }

    public function kota()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\City::class, 'kodeKota', 'code');
    }

    public function kecamatan()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\District::class, 'kodeKecamatan', 'code');
    }

    public function faskes()
    {
        return $this->belongsTo(\App\Models\Master\MasterFaskes::class, 'kodeFaskes', 'kodeFaskes');
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->userid)) {
                $model->userid = (string) Str::uuid();
            }
        });
    }
}

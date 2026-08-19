<?php

namespace App\Models\UserPanel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use App\Models\UserPanel\UserGroup;
use App\Models\UserPanel\UserRole;
use App\Models\Master\MasterFaskes;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;


class UserApp extends Model
{
    protected $table = 'users_app';

    protected $primaryKey = 'userid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'userid',
        'username',
        'groupid',
        'role_id',
        'email',
        'namalengkap',
        'kodeFaskes',
        'namaFaskes',
        'kodePropinsi',
        'kodeKota',
        'kodeKecamatan',
        'password',
        'api_token',
        'role',
    ];

    protected $hidden = [
        'password',
        'api_token',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            if (!$model->userid) {
                $model->userid = (string) Str::uuid();
            }

        });
    }

    /*
    |--------------------------------------------------------------------------
    | GROUP
    |--------------------------------------------------------------------------
    */

    public function group(): BelongsTo
    {
        return $this->belongsTo(
            UserGroup::class,
            'groupid',
            'group_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ROLE
    |--------------------------------------------------------------------------
    */

    public function roleData(): BelongsTo
    {
        return $this->belongsTo(
            UserRole::class,
            'role_id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PROVINSI
    |--------------------------------------------------------------------------
    */

    public function province(): BelongsTo
    {
        return $this->belongsTo(
            Province::class,
            'kodePropinsi',
            'code'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | KOTA / KABUPATEN
    |--------------------------------------------------------------------------
    */

    public function city(): BelongsTo
    {
        return $this->belongsTo(
            City::class,
            'kodeKota',
            'code'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | KECAMATAN
    |--------------------------------------------------------------------------
    */

    public function district(): BelongsTo
    {
        return $this->belongsTo(
            District::class,
            'kodeKecamatan',
            'code'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FASKES
    |--------------------------------------------------------------------------
    */

    public function faskes(): BelongsTo
    {
        return $this->belongsTo(
            MasterFaskes::class,
            'kodeFaskes',
            'kodeFaskes'
        );
    }
}
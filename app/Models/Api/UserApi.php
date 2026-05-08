<?php
namespace App\Models\Api;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserApi extends Authenticatable
{
    use HasApiTokens;


    protected $table = 'users_api';

    protected $fillable = [
        'id',
        'username',
        'email',
        'password',
        'client_name',
        'is_active'
    ];

    protected $hidden = [
        'password'
    ];

    public $incrementing = false;
    protected $keyType = 'string';
}

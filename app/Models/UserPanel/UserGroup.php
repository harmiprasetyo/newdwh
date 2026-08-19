<?php

namespace App\Models\UserPanel;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserPanel\UserRole;
use App\Models\UserPanel\UserApp;

class UserGroup extends Model
{
    protected $table = 'usergroups';

    protected $primaryKey = 'group_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'group_name',
    ];


    /**
     * Roles yang dimiliki group
     */
    public function roles()
    {
        return $this->hasMany(
            UserRole::class,
            'groupId',
            'group_id'
        );
    }


    /**
     * Users yang menggunakan group ini
     */
    public function users()
    {
        return $this->hasMany(
            UserApp::class,
            'groupid',
            'group_id'
        );
    }
}
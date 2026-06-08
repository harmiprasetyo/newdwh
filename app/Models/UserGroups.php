<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserRoles;

class UserGroups extends Model
{


protected $table = 'usergroups';
protected $primaryKey = 'group_id';
protected $fillable = ['group_name'];

 public function roles()
    {
        return $this->hasMany(UserRoles::class, 'groupId', 'group_id');
    }

}

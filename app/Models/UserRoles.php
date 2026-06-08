<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{
    use HasFactory;
    protected $table = 'user_roles';
    protected $fillable = ['role_name', 'groupId'];

public function group()
{
    return $this->belongsTo(\App\Models\UserGroups::class, 'groupId', 'group_id');
}


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGroups extends Model
{
protected $table = "usergroups";
protected $fillable = ["group_name"];
use HasFactory;
}

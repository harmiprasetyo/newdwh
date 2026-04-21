<?php
namespace App\Models\users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class authUser extends Authenticatable
{
    use HasFactory;

    protected $table = "userapp";

    protected $primaryKey = 'userId'; // 🔥 INI KUNCI

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        "password",
        "userName",
        "email",
        "userFullName",
        "userGroupId"
    ];

    public function getAuthIdentifierName()
    {
        return 'userName';
    }
}

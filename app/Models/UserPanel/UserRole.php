<?php

namespace App\Models\UserPanel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserRole extends Model
{
    protected $table = 'user_roles';

    protected $primaryKey = 'id';

    protected $fillable = [
        'role_name',
        'groupId',
    ];

    /**
     * Role belongs to Group
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(
            UserGroup::class,
            'groupId',
            'group_id'
        );
    }

    /**
     * Role has many users
     */
    public function users(): HasMany
    {
        return $this->hasMany(
            UserApp::class,
            'role_id',
            'id'
        );
    }
}
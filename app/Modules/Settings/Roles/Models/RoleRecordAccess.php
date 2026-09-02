<?php

namespace App\Modules\Settings\Roles\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleRecordAccess extends Model
{
    protected $table = 'role_record_access';

    protected $fillable = [
        'role_id',
        'module',
        'access_level',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
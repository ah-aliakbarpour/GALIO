<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 * @property $user_id
 * @property $is_main_admin
 * @property $role
 * @property $create_at
 * @property $update_at
 */

class Admin extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'is_main_admin', 'role'];

    const CREATED_AT = null;  // This column doesn't exist (We don't need)
    const UPDATED_AT = null;  // This column doesn't exist (We don't need)


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

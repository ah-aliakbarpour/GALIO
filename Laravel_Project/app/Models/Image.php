<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 * @property $imageable_id
 * @property $imageable_type
 * @property $name  Save only the name of the image instead of the full path
 * @property $create_at
 * @property $update_at
 */

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['imageable_id', 'imageable_type', 'name'];


    public function imageable()
    {
        return $this->morphTo();
    }

}

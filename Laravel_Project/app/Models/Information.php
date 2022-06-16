<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 * @property $product_id
 * @property $key
 * @property $value
 * @property $created_at
 * @property $updated_at
 */

class Information extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'key', 'value'];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

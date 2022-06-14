<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 * @property $user_id
 * @property $coupon
 * @property $create_at
 * @property $update_at
 */

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'coupon'];

    const SHIPPING_PRICE = 70;


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }
}

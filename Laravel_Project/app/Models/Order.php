<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 * @property $user_id
 * @property $status
 * @property $country
 * @property $city
 * @property $address
 * @property $phone
 * @property $payment_method
 * @property $shipping
 * @property $coupon
 * @property $total_price
 * @property $create_at
 * @property $update_at
 */

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'status', 'country', 'city', 'address', 'phone',
        'payment_method', 'shipping', 'coupon', 'total_price'];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity', 'price');
    }
}

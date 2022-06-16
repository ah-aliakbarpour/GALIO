<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Columns of reviews table
 *
 * @property $id
 * @property $code
 * @property $price
 * @property $create_at
 * @property $update_at
 */

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'price'];
}

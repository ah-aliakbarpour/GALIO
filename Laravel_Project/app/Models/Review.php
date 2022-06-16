<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 * @property $product_id
 * @property $rating
 * @property $context
 * @property $author_name
 * @property $author_email
 * @property $approved
 * @property $create_at
 * @property $update_at
 */

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'rating', 'context', 'author_name', 'author_email', 'approved', 'approved_at'];

    const UPDATED_AT = null;  // This column doesn't exist (We don't need)


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function approve()
    {
        return $this->update([
            'approved' => '1',
            'approved_at' => now(),
        ]);
    }
}

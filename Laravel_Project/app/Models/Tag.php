<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 * @property $type
 * @property $title
 * @property $create_at
 * @property $update_at
 */

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'title'];


    public function scopeProduct(Builder $query)
    {
        $query->where('type', '=', 'product');
    }

    public function scopeBlog(Builder $query)
    {
        $query->where('type', '=', 'blog');
    }


    public function products()
    {
        return $this->morphedByMany(Product::class, 'taggable');
    }

    public function blogs()
    {
        return $this->morphedByMany(Blog::class, 'taggable');
    }

}

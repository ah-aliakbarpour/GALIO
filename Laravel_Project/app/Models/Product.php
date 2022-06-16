<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property $id
 * @property $category_id
 * @property $title
 * @property $description
 * @property $label
 * @property $stock
 * @property $price
 * @property $discount
 * @property $created_at
 * @property $updated_at
 *
 * @property $images
 */

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'title','introduction', 'description', 'stock', 'price', 'discount'];

    const IMAGES_DIR = 'images/product';  // Using public disk


    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function information()
    {
        return $this->hasMany(Information::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


    public function imagePath(Image $image)
    {
        return asset('storage/' . self::IMAGES_DIR . '/' . $image->name);
    }

    public function deleteImages()
    {
        // Delete image files
        foreach ($this->images as $image)
            Storage::disk('public')->delete(self::IMAGES_DIR . '/' . $image->name);

        // Delete images from database
        $this->images()->delete();
    }

    public function deleteCompletely()
    {
        // Delete images
        $this->deleteImages();

        // Detach tags
        $this->tags()->detach();

        // Delete product
        $this->delete();
    }

    public function rating()
    {
        return round($this->reviews()
            ->where('approved', '=', '1')
            ->avg('rating'), 1);
    }

    public function reviewsNumber()
    {
        return $this->reviews()
            ->where('approved', '=', '1')
            ->count();
    }

    public function label()
    {
        if (!$this->stock)
            return null;

        if ($this->rating() >= 4)
            return 'TOP';

        if ($this->discount)
            return 'OFF';

        // If product created at last 7 days
        if ($this->created_at->gte(Carbon::now()->subWeek()))
            return 'NEW';

        return null;
    }

}

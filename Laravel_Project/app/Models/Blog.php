<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property $id
 * @property $category_id
 * @property $title
 * @property $context
 * @property $author
 * @property $create_at
 * @property $update_at
 *
 * @property $images
 */

class Blog extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'title', 'context', 'author'];

    const IMAGES_DIR = 'images/blog';  // Using public disk


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

    public function comments()
    {
        return $this->hasMany(Comment::class);
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

        // Delete blog
        $this->delete();
    }

    public function commentsNumber()
    {
        return $this->comments()
            ->where('approved', '=', '1')
            ->count();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property $id
 * @property $image_name
 * @property $first_header
 * @property $second_header
 * @property $paragraph
 * @property $link
 * @property $create_at
 * @property $update_at
 */

class Banner extends Model
{
    use HasFactory;

    protected $fillable = ['image_name', 'first_header', 'second_header', 'paragraph', 'link'];

    const IMAGES_DIR = 'images/banner';  // Using public disk


    public function imagePath()
    {
        return asset('storage/' . self::IMAGES_DIR . '/' . $this->image_name);
    }

    public function deleteImageFile()
    {
        Storage::disk('public')->delete(self::IMAGES_DIR . '/' . $this->image_name);
    }

    public function deleteCompletely()
    {
        // Delete image file
        $this->deleteImageFile();

        // Delete banner
        $this->delete();
    }
}

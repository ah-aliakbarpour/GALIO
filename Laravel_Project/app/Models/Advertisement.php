<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property $id
 * @property $position
 * @property $image_name
 * @property $link
 * @property $create_at
 * @property $update_at
 */

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = ['position', 'image_name', 'link'];


    const IMAGES_DIR = 'images/advertisement';  // Using public disk


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

        // Delete advertisement
        $this->delete();
    }
}

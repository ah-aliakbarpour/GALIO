<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $id
 * @property $blog_id
 * @property $context
 * @property $author_name
 * @property $author_email
 * @property $approved
 * @property $create_at
 * @property $update_at
 */

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['blog_id', 'context', 'author_name', 'author_email', 'approved', 'approved_at'];

    const UPDATED_AT = null;  // This column doesn't exist (We don't need)


    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    public function approve()
    {
        return $this->update([
            'approved' => '1',
            'approved_at' => now(),
        ]);
    }
}

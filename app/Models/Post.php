<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Post extends Model
{
    use Translatable;

    protected $fillable = [
        'author',
    ];

    public $translatedAttributes = [
        'title',
        'content',
    ];

    /**
     * Get all translations for the post.
     */
    public function translations()
    {
        return $this->hasMany(
            PostTranslation::class,
            'post_id'
        );
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title',
        'content',
    ];

    /**
     * Translation belongs to a post.
     */
    public function post()
    {
        return $this->belongsTo(
            Post::class,
            'post_id'
        );
    }
}
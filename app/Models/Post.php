<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;

class Post extends Model
{
    use Translatable;

    protected $fillable = ['author'];

    public $translatedAttributes = ['title', 'content'];
}

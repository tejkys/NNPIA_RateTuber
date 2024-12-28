<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $table = 'channels';
    protected $fillable = [
        'yt_id',
        'name',
        'description',
        'active',
        'country',
        'subscribers',
        'views',
        'videos',
        'avg_views',
        'avg_likes',
        'avg_comments',
        'published',
    ];
    public $timestamps = true;
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'channel_categories', 'channel_id', 'category_id');

    }
}

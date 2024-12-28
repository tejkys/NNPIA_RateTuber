<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    protected $fillable = [
        'user_id',
        'channel_id',
        'text',
        'rating',
        'rating_age',
        'rating_violence',
        'rating_fear',
        'rating_sex',
        'rating_coarse_language',
        'rating_discrimination',
        'rating_drugs',
    ];
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
}

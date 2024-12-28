<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelCategories extends Model
{
    protected $table = 'channel_categories';
    protected $fillable = [
        'channel_id',
        'category_id',
    ];
    public $timestamps = false;
}

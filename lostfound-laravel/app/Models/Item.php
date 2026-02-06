<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
     protected $fillable = [
        'user_id',
        'type',
        'title',
        'description',
        'category',
        'location',
        'date_occurred',
        'photo_url',
        'status'
        ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

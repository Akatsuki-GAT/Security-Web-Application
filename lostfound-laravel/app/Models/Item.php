<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; 

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
        'image_path',
        'status'
        ];

        protected static function booted()
    { 
        static::deleting(function ($item) { 
            if ($item->image_path && Storage::disk('public')->exists($item->image_path)) { //added this for delete
                Storage::disk('public')->delete($item->image_path); //In a public first
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferImage extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getImageUrlAttribute()
    {
        return asset('storage/'.$this->image);
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    protected $appends = ['image_url'];
}

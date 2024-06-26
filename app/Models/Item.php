<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function getEncryptedIdAttribute()
    {
        return encrypt($this->id);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'item_id', 'item_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function getImageUrlAttribute()
    {
        return asset('storage/'.$this->image_name);
    }

    protected $appends = ['encrypted_id', 'image_url'];
}

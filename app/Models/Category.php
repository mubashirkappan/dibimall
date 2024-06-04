<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getEncryptedIdAttribute()
    {
        return encrypt($this->id);
    }

    public function Shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function getImageUrlAttribute()
    {
        return asset('storage/'.$this->image_name);
    }

    protected $appends = ['encrypted_id', 'image_url'];
}

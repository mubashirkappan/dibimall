<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
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

    public function Shop()
    {
        return $this->belongsTo(Shop::class);
    }


    protected $appends = ['encrypted_id'];
}

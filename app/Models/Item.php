<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
    protected $appends = ['encrypted_id'];

}

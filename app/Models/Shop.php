<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shop extends Model
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
    public function type()
    {
        return $this->belongsTo(Type::class);
    }
    public function Place()
    {
        return $this->belongsTo(Place::class);
    }
    public function items()
    {
        return $this->hasMany(Item::class);
    }
    public function Categories()
    {
        return $this->hasMany(Category::class,);
    }
    protected $appends = ['encrypted_id'];

}

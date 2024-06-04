<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->hasMany(Category::class);
    }

    public function getImageUrlAttribute()
    {
        return asset('storage/'.$this->image_name);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
        // , 'id', 'item_id'
    }
    protected $appends = ['encrypted_id', 'image_url'];
}

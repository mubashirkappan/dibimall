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
        return $this->hasOne(Type::class,'id','type_id');
    }
    public function Place()
    {
        return $this->hasOne(Place::class,'id','place_id');
    }
    public function Items()
    {
        return $this->hasManyThrough(Item::class,Category::class,);
    }
    public function Categories()
    {
        return $this->hasMany(Category::class,);
    }
    protected $appends = ['encrypted_id'];

}

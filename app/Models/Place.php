<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
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

    public function Users()
    {
        return $this->hasMany(User::class, 'id', 'shop_id');
    }

    public function Shops()
    {
        return $this->hasMany(Shop::class, 'id', 'shop_id');
    }

    protected $appends = ['encrypted_id'];
}

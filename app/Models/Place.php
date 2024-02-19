<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Place extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getEncryptedIdAttribute()
    {
      return encrypt($this->id);
    }    
    public function Users()
    {
        return $this->hasMany(User::class,'id','shop_id');
    }
    public function Shops()
    {
        return $this->hasMany(Shop::class,'id','shop_id');
    }
    protected $appends = ['encrypted_id'];

}

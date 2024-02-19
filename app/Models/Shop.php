<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shop extends Model
{
    use HasFactory;
    protected $guarded = [];

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
    protected $appends = ['encrypted_id'];

}

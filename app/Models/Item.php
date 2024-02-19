<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function getEncryptedIdAttribute()
    {
      return encrypt($this->id);
    }   
    public function category()
    {
        return $this->hasOne(category::class,'id','shop_id');
    } 
    protected $appends = ['encrypted_id'];

}

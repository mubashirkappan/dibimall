<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
      protected $guarded = [];

    public function getEncryptedIdAttribute()
    {
      return encrypt($this->id);
    }    
    public function Customer()
    {
        return $this->hasOne(Customer::class,'id','customer_id');
    }
    public function Item()
    {
        return $this->hasOne(Item::class,'id','item_id');
    }
    protected $appends = ['encrypted_id'];
}

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
    protected $appends = ['encrypted_id'];

}

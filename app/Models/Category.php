<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getEncryptedIdAttribute()
    {
      return encrypt($this->id);
    }    
    public function Shop()
    {
        return $this->belongsTo(Shop::class);
    }
    protected $appends = ['encrypted_id'];
}

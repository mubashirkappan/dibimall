<?php

namespace App\Models;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function getEncryptedIdAttribute()
    {
      return encrypt($this->id);
    }    
    public function place()
    {
        return $this->hasOne(Place::class,'id','place_id');
    }
    protected $appends = ['encrypted_id'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

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
        return $this->belongsTo(Place::class);
    }

    protected $appends = ['encrypted_id'];
}

<?php

namespace App\Models;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Model
{
    use AuthenticableTrait, HasApiTokens, HasFactory;
    use SoftDeletes;
    
    protected $guarded =[];
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    public function order()
    {
        return $this->belongsTo(Order::class);
        // , 'id', 'item_id'
    }
    public function cart()
    {
        return $this->belongsTo(Cart::class);
        // , 'id', 'item_id'
    }

    protected $guarded = [];
}

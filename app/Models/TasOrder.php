<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TasOrder extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(TasOrderItem::class);
    }
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
    protected static function booted()
    {
        static::deleting(function ($order) {
            // This deletes all related items first
            $order->items()->delete();
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('M j, Y h:i A');
    }

    public function getEncryptedIdAttribute()
    {
        return encrypt($this->id);
    }

    public function Customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function orderItem()
    {
        return $this->hasOne(OrderItem::class);
    }

    public function order()
    {
        return $this->hasOneThrough(Order::class, OrderItem::class, 'id', 'id', 'id', 'order_id');
    }
    // public function order()

    // {
    //     return $this->orderItem()->order;
    // }

    public function Item()
    {
        return $this->belongsTo(Item::class);
        // , 'id', 'item_id'
    }

    // public function shop()
    // {
    //     return $this->hasOneThrough(Shop::class,Item::class,'id', 'id', 'id', 'shop_id');
    // }
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
    // public function shop()
    // {
    //     // Define a custom method to access the shop through the item
    //     return $this->item->shop();
    // }
    public function getImageUrlAttribute()
    {
        return asset('storage/'.$this->item_image_name);
    }


    protected $appends = ['encrypted_id','image_url'];
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'dibi_price' => $this->dibi_price,
            'count' => $this->count,
            'image_name' => $this->image_name,
            'category_id' => $this->category_id,
            'shop_id' => $this->shop_id,
            'active' => $this->active,
            'offer' => $this->offer,
            'percentage' => $this->percentage,
            'amount' => $this->amount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

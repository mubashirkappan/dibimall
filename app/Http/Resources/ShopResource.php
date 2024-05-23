<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'landmark' => $this->landmark,
            'country_code' => $this->country_code,
            'phone' => $this->phone,
            'email' => $this->email,
            'logo_name' => $this->logo_name,
            'category_count' => $this->category_count,
            'image_count' => $this->image_count,
            'type_id' => $this->type_id,
            'delivery' => $this->delivery,
            'km' => $this->km,
            'take_away' => $this->take_away,
            'top_shop' => $this->top_shop,
            'active' => $this->active,
            'place_id' => $this->place_id,
            'place' => $this->place->name,
            'categorys' => $this->Categories,
            'encrypt_id' => $this->encrypted_id,
        ];
    }
}

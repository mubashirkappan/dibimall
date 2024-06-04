<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'name' => $this->name,
            'image_name' => $this->image_url,
            'encrypted_id' => $this->encrypted_id,
            'active' => $this->active,
        ];
    }
}

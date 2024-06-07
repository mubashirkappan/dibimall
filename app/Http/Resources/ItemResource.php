<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'db_price' => $this->dibi_price,
            'available_count' => $this->count,
            'image_name' => $this->image_url,
            'category_id' => $this->category_id,
            'active' => $this->active,
            'encrypted_id' => $this->encrypted_id,
        ];
    }
}

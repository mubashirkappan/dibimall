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
            'count' => $this->count,
            'image_name' => $this->image_name,
            'category_id' => $this->category_id,
            'active' => $this->active,
        ];
    }
}


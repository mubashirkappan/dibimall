<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListConfirmOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [    
            'item' => $this->Item->name,
            'shop' => $this->shop->name,
            'total_price'=>$this->total_price,
            'quantity'=>$this->count
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'phonenumber' => $this->phonenumber,
            'whatsapp_number' => $this->whatsapp_number,
            'is_owner' => 0,
        ];
        if($this->user_type == 1){
            $data['is_owner'] = 1;
        }
        return $data;
    }
}
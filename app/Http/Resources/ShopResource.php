<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'email' => $this->user->email,
            'address' => $this->address,
            'image_url' => $this->image_url,
            'description' => $this->description,
            'phone' => $this->phone,
            'is_open' => $this->is_open,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_verified'=> $this->user->email_verified_at !== null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}

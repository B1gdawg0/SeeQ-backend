<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchShopResource extends JsonResource
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
            'name' => $this->name,
            'address' => $this->address,
            'image_url' => $this->image_url,
            'phone' => $this->phone,
            'description' => $this->description,
            'is_open' => $this->is_open,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'queues' => SearchQueueResource::collection($this->whenLoaded('queues')),
        ];
    }
}

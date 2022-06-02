<?php

namespace App\Http\Resources\Advertisement;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AdvertisementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'user_id' => $this->user_id,
            'images' => AdvertisementImageResource::collection($this->whenLoaded('images')),
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d h:i:s')
        ];
    }
}

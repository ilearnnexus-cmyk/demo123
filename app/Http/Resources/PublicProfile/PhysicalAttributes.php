<?php

namespace App\Http\Resources\PublicProfile;

use Illuminate\Http\Resources\Json\JsonResource;

class PhysicalAttributes extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'height' => $this->height,
            'weight' => $this->weight,
            'eye_color' => $this->eye_color,
            'hair_color' => $this->hair_color,
            'complexion' => $this->complexion,
            'blood_group' => $this->blood_group,
            'body_type' => $this->body_type,
            'body_art' => $this->body_art,
            'disability' => $this->disability,
        ];
    }
}

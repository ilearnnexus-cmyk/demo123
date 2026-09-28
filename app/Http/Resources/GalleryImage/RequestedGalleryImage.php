<?php

namespace App\Http\Resources\GalleryImage;

use Illuminate\Http\Resources\Json\JsonResource;

class RequestedGalleryImage extends JsonResource
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
            'image_path'=> static_asset('assets/img/placeholder.jpg')     
        ];
    }
}

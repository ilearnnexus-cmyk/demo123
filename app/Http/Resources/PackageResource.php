<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
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
            'package_id'           => $this->id,
            'name'                 => $this->name,
            'image'                => uploaded_asset($this->image)?? static_asset('assets/img/placeholder.jpg'),
            'express_interest'     => $this->express_interest,
            'photo_gallery'        => $this->photo_gallery ,
            'contact'              => $this->contact,
            'profile_image_view'   => $this->profile_image_view,
            'gallery_image_view'   => $this->gallery_image_view,
            'auto_profile_match'   => $this->auto_profile_match,
            'price'                => $this->price,
            'validity'             => $this->validity
        ];
    }
}

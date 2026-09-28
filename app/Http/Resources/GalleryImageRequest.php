<?php

namespace App\Http\Resources;

use App\Models\ViewGalleryImage;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class GalleryImageRequest extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $view_gallery_images = ViewGalleryImage::find($this->id);
        $user = User::where('id', $view_gallery_images->requested_by)->first();
        if ($user != null) {
            $age = Carbon::parse($user->member->birthday)->age;
            return [
                'id' => $this->id,
                'photo' => uploaded_asset($user->photo) ?? static_asset('assets/img/avatar-place.png'),
                'name' => $user->first_name . $user->last_name,
                'date_of_birth' => $age,
                'status' => $view_gallery_images->status,
            ];
        }
    }
}

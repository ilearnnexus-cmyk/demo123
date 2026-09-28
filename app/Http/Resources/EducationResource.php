<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EducationResource extends JsonResource
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
            'id' => $this->id,
            'degree' => $this->degree ?? '',
            'institution' => $this->institution ?? '',
            'start' => $this->start ?? '',
            'end' => $this->end ?? '',
            'present' => $this->present == 1 ? true : false,
        ];
    }
}

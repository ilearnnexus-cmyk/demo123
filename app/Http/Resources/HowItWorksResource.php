<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HowItWorksResource extends JsonResource
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
            'step'     =>  $this['step'],
            'title'     =>  $this['title'],
            'subtitle'     =>  $this['subtitle'],
            'icon'     =>  $this['icon'],
        ];
    }
}

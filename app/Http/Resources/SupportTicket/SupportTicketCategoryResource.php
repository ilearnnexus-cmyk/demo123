<?php

namespace App\Http\Resources\SupportTicket;

use Illuminate\Http\Resources\Json\JsonResource;

class SupportTicketCategoryResource extends JsonResource
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
            'name' => $this->name,

        ];
    }
}

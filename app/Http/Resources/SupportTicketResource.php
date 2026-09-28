<?php

namespace App\Http\Resources;

use App\Http\Resources\SupportTicket\SupportTicketReply;
use Illuminate\Http\Resources\Json\JsonResource;

class SupportTicketResource extends JsonResource
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
            'ticket_id' => $this->ticket_id,
            'status' => $this->status,
            'subject' => $this->subject,
            'attachments' => uploaded_asset($this->attachments),
            'description' => str_replace('&amp;', '&', str_replace('&nbsp;', ' ', strip_tags($this->description))),
            'support_category_name' => $this->supportCategory->name,
            'created_at' => $this->created_at,
            'reply' => SupportTicketReply::collection($this->supportTicketReplies),
        ];
    }
}

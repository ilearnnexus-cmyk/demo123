<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $approval = 'N/A';
        if($this->offline_payment == 1){
            $approval = $this->approval == 1 ? 'Approved' : 'Pending';
        }

        return [
            'date'           => date('d-m-Y', strtotime($this->created_at)),
            'amount'         => single_price($this->amount),
            'payment_method' => ucfirst(str_replace('_', ' ', $this ->payment_method)),
            'approval'       => $approval,
        ];
    }
}

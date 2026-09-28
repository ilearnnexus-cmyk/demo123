<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletWithdrawRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->status == 0){
            $status = translate('Pending');
        }else{
            $status = $this->status == 1 ? translate('Accepted') : translate('Rejected');
        }

        return [
            'amount' => single_price($this->amount),
            'status'   => $status,
            'details'   => $this->details,
            'date'   => date('d-m-Y', strtotime($this->created_at)),
        ];
    }
}

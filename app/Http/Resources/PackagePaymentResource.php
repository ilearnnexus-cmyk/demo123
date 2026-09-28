<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackagePaymentResource extends JsonResource
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
            'package_payment_id' => $this->id,
            'payment_code'       => $this->payment_code,
            'package_name'       => $this->package->name,
            'payment_method'     => $this->payment_method == "manual_payment" ? $this->custom_payment_name : ucwords($this->payment_method),
            'amount'             => single_price($this->amount),
            'payment_status'     => $this->payment_status == 'Paid' ? 'Paid' : 'Unpaid',
            'date'               => date('d-m-Y h:i:s',strtotime($this->created_at))
        ];
    }
}

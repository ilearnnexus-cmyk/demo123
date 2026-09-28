<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PackagePaymentInvoiceResource extends JsonResource
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
            'purchase_code'   => $this->payment_code,
            'purchase_date'   => date('d-m-Y h:i:s', strtotime($this->created_at)),
            'name'            => $this->user->first_name.' '.$this->user->last_name,
            'email'           => $this->user->email,
            'phone'           => $this->user->phone,
            'payment_method'  => $this->payment_method == "manual_payment" ? $this->custom_payment_name : ucwords($this->payment_method),
            'payment_status'  => $this->payment_status == 'Paid' ? 'Paid' : 'Unpaid',
            'package_name'    => $this->package->name,
            'amount'          => single_price($this->amount),
            'total'           => single_price($this->amount),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nic_no' => $this->nic_no,
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
            'status' => $this->status,
            'nic_front' => $this->nic_front ? url('api/media/' . $this->nic_front) : null,
            'nic_back' => $this->nic_back ? url('api/media/' . $this->nic_back) : null,
            'license_front' => $this->license_front ? url('api/media/' . $this->license_front) : null,
            'license_back' => $this->license_back ? url('api/media/' . $this->license_back) : null,
            'rental_history' => BookingResource::collection($this->whenLoaded('bookings')),
        ];
    }
}

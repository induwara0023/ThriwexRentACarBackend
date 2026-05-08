<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pickup_datetime' => $this->pickup_datetime,
            'return_datetime' => $this->return_datetime,
            'pickup_km' => $this->pickup_km,
            'return_km' => $this->return_km,
            'advance_payment' => $this->advance_payment,
            'total_price' => $this->total_price,
            'security_item_description' => $this->security_item_description,
            'status' => $this->status,
            'vehicle' => new VehicleResource($this->whenLoaded('vehicle')),
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'media' => BookingMediaResource::collection($this->whenLoaded('media')),
        ];
    }
}

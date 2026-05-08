<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'plate_no' => $this->plate_no,
            'model' => $this->model,
            'type' => $this->type,
            'transmission' => $this->transmission,
            'current_km' => $this->current_km,
            'next_service_km' => $this->next_service_km,
            'insurance_expiry' => $this->insurance_expiry,
            'license_expiry' => $this->license_expiry,
            'status' => $this->status,
            'daily_rate' => $this->daily_rate,
            'km_limit_per_day' => $this->km_limit_per_day ?? 0,
            'extra_km_rate' => $this->extra_km_rate ?? 0,
            'image_url' => $this->image_path ? asset('storage/' . $this->image_path) : null,
        ];
    }
}

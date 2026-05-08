<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'vehicle_id' => 'required|exists:vehicles,id',
            'customer_id' => 'required|exists:customers,id',
            'pickup_date' => 'required|date',
            'pickup_km' => 'required|integer',
            'advance_payment' => 'nullable|numeric|min:0',
            // Image validation
            'selfie' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'nic' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'agreement' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompleteBookingRequest extends FormRequest
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
            'return_km' => 'required|integer|gt:pickup_km_check',
            'return_date' => 'nullable|date|after_or_equal:pickup_date_check',
        ];
    }

    /**
     * Prepare data for validation.
     */
    protected function prepareForValidation()
    {
        // This is a placeholder; we'll handle the actual comparison in the controller 
        // or via a custom rule if needed, but for simplicity we'll pass values here.
    }
}

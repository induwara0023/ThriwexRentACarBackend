<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function search($nic)
    {
        $customer = Customer::where('nic_no', $nic)->first();

        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        // Load all bookings with their vehicles for full evidence history
        $customer->load(['bookings' => function ($query) {
            $query->with(['vehicle', 'media'])->latest();
        }]);

        return new CustomerResource($customer);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nic_no' => 'required|unique:customers,nic_no',
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'status' => 'required|in:active,blacklisted',
            'nic_front' => 'nullable|image|max:2048',
            'nic_back' => 'nullable|image|max:2048',
            'license_front' => 'nullable|image|max:2048',
            'license_back' => 'nullable|image|max:2048',
        ]);

        $fields = ['nic_front', 'nic_back', 'license_front', 'license_back'];
        foreach ($fields as $field) {
            if ($request->hasFile($field)) {
                $validated[$field] = $request->file($field)->store('customers/documents', 'public');
            }
        }

        $customer = Customer::create($validated);
        return new CustomerResource($customer);
    }

    public function index()
    {
        return CustomerResource::collection(Customer::latest()->get());
    }
}

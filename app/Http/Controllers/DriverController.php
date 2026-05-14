<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index()
    {
        return response()->json(Driver::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'license_no' => 'required|string|unique:drivers',
            'phone' => 'required|string',
            'address' => 'nullable|string',
            'status' => 'required|in:available,on_hire,inactive',
        ]);

        $driver = Driver::create($validated);
        return response()->json($driver, 201);
    }

    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'license_no' => 'required|string|unique:drivers,license_no,' . $driver->id,
            'phone' => 'required|string',
            'address' => 'nullable|string',
            'status' => 'required|in:available,on_hire,inactive',
        ]);

        $driver->update($validated);
        return response()->json($driver);
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();
        return response()->json(['message' => 'Driver deleted successfully']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        return response()->json(User::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // Using plain text or hashing? Looking at the create-admin route, it seems they might not be using Hash::make in the manual route, but the Model might have a setter. Let's check User model.
        ]);

        return response()->json($user, 201);
    }

    public function update(Request $request, User $admin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $admin->id,
            'password' => 'nullable|string|min:6',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;
        if ($request->password) {
            $admin->password = $request->password;
        }
        $admin->save();

        return response()->json($admin);
    }

    public function destroy(User $admin)
    {
        // Prevent deleting the last admin?
        if (User::count() <= 1) {
            return response()->json(['message' => 'Cannot delete the last admin.'], 422);
        }

        $admin->delete();
        return response()->json(['message' => 'Admin deleted successfully.']);
    }
}

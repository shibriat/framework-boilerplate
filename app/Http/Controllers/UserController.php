<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function updateRoles(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'required|array',
        ]);

        // Update user roles
        $user->syncRoles($request->input('roles'));

        return response()->json(['message' => 'User roles updated successfully']);
    }
}

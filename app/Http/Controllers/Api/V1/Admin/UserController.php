<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends BaseController
{
    public function index()
    {
        return $this->sendResponse(User::all(), 'Users retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required | string',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $User = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password'])
        ]);

        return $this->sendResponse($User, 'User created successfully.');
    }

    public function show(User $user)
    {
        return $this->sendResponse($user, 'User retrieved successfully.');
    }

    public function update(Request $request, User $user)
    {

        $validatedData = $request->validate([
            'name' => 'sometimes | string',
            'email' => 'sometimes|email|unique:users',
            'password' => 'sometimes'
        ]);

        $user->update($validatedData);

        return $this->sendResponse($user, 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return $this->sendResponse([], 'User deleted successfully.');
    }

    public function restore(Request $request, $id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return $this->sendResponse($user, 'User restored successfully.');
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return $this->sendError('User not found', 'The requested user does not exist', 404);
        }

        $request->validate([
            'role' => 'required|in:super_admin,g,user_manager'
        ]);

        $user->syncRoles([$request->role]);

        return $this->sendResponse(
            $user->load('roles'),
            'Role updated successfully'
        );
    }
}

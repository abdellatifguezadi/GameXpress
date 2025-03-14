<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function update(Request $request, User $user){

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

}

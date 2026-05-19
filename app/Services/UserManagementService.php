<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserManagementService
{
    public function getAllUsers()
    {
        return User::with('department')->get();
    }

    public function getUserById($id)
    {
        return User::with('department')->find($id);
    }

    public function register(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'department_id' => $data['department_id'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function updateUser($id, array $data)
    {
        $user = $this->getUserById($id);
        if (!$user) {
            return null;
        }
        $user->update($data);
        return $user;
    }
}

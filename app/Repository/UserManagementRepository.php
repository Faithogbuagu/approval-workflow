<?php

namespace App\Repository;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserManagementRepository
{
    public function createUser(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'department_id' => $data['department_id'],
            'password' => Hash::make($data['password']),
        ]);
    }

    
}
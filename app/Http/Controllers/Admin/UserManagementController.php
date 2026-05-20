<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UpdateUserRequest; 
use App\Services\UserManagementService;
use App\Http\Requests\AccountCreationRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function __construct(protected UserManagementService $userManagementService){}

    public function index()
    {
        $users = $this->userManagementService->getAllUsers();

        return response()->json($users, 200);
    }

    public function show($id)
    {
        $user = $this->userManagementService->getUserById($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user, 200);
    }
    /**
     * Handle account creation requests.
     * @param AccountCreationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AccountCreationRequest $request)
    {
        $user = $this->userManagementService->register($request->validated());

        if (!$user) {
            return response()->json(['message' => 'Failed to create user. Department may be inactive.'], 422);
        }

        return response()->json([
            'message' => 'Account created successfully',
            'user' => $user
        ], 201);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = $this->userManagementService->updateUser($id, $request->validated());

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user === false) {
            return response()->json(['message' => 'Failed to update user. Department may be inactive.'], 422);
        }

        return response()->json($user, 200);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}

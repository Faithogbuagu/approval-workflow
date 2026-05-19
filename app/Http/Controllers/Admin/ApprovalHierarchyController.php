<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ApprovalHierarchyRequest;
use App\Http\Resources\ApprovalHierarchyResource;
use App\Services\ApprovalHierarchyService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApprovalHierarchyController extends Controller
{
    public function __construct(protected ApprovalHierarchyService $approvalHierarchyService){}

    public function index()
    {
        $hierarchies = $this->approvalHierarchyService->getAllHierarchies();
        return ApprovalHierarchyResource::collection($hierarchies, 200);
    }

    public function show($id)
    {
        $hierarchy = $this->approvalHierarchyService->getHierarchyById($id);

        if (!$hierarchy) {
            return response()->json(['message' => 'Approval hierarchy not found'], 404);
        }

        return new ApprovalHierarchyResource($hierarchy, 200);
    }

    public function store(ApprovalHierarchyRequest $request)
    {
        $hierarchy = $this->approvalHierarchyService->createHierarchy($request->validated());

        if (!$hierarchy) {
            return response()->json(['message' => 'Failed to create approval hierarchy'], 500);
        }

        return new ApprovalHierarchyResource($hierarchy, 201);
    }

    public function update(ApprovalHierarchyRequest $request, $id)
    {
        $hierarchy = $this->approvalHierarchyService->updateHierarchy($id, $request->validated());

        if ($hierarchy === null) {
            return response()->json(['message' => 'Approval hierarchy not found'], 404);
        }

        if ($hierarchy === false) {
            return response()->json(['message' => 'Failed to update approval hierarchy'], 500);
        }

        return new ApprovalHierarchyResource($hierarchy, 200);
    }

    public function destroy($id)
    {
        $deleted = $this->approvalHierarchyService->deleteHierarchy($id);

        if ($deleted === null) {
            return response()->json(['message' => 'Approval hierarchy not found'], 404);
        }

        if ($deleted === false) {
            return response()->json(['message' => 'Failed to delete approval hierarchy'], 500);
        }

        return response()->json(['message' => 'Approval hierarchy deleted successfully'], 200);
    }
}

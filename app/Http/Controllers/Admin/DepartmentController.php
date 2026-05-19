<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Services\DepartmentService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct(protected DepartmentService $departmentService){}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = $this->departmentService->getAllDepartments();
        return DepartmentResource::collection($departments, 200);
    }

    public function show($id)
    {
        $department = $this->departmentService->getDepartmentById($id);

        if (!$department) {
            return response()->json(['message' => 'Department not found'], 404);
        }

        return new DepartmentResource($department, 200);
    }
    
    public function store(CreateDepartmentRequest $request)
    {
        $department = $this->departmentService->createDepartment($request->validated());

        return new DepartmentResource($department, 201);
    }

    public function update(UpdateDepartmentRequest $request, $id)
    {
        $department = $this->departmentService->updateDepartment($id, $request->validated());

        if (!$department) {
            return response()->json(['message' => 'Department not found'], 404);
        }

        return new DepartmentResource($department, 200);
    }

    public function destroy($id)
    {
        $deleted = $this->departmentService->deleteDepartment($id);

        if (!$deleted) {
            return response()->json(['message' => 'Department not found'], 404);
        }
        if ($deleted === false) {
            return response()->json(['message' => 'Cannot delete department with associated users'], 400);
        }

        return response()->json(['message' => 'Department deleted successfully'], 200);
    }
}

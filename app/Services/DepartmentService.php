<?php

namespace App\Services;

use App\Models\Department;


class DepartmentService
{
    public function getAllDepartments()
    {
        return Department::all();
    }

    public function getDepartmentById($id)
    {
        return Department::find($id);
    }

    public function createDepartment(array $data)
    {
        return Department::create([
                'name' => $data['name'],
                'status' => 'Active',
                'created_by' => auth()->id(),
            ]);
    }

    public function updateDepartment($id, array $data)
    {
        $department = Department::find($id);
        if (!$department) {
            return null;
        }
        $department->update([
            'name' => $data['name'],
            'status' => $data['status'],
            'deleted_by' => $data['status'] === 'Inactive' ? auth()->id() : $department->deleted_by,
        ]);
        return $department;
    }

    public function deleteDepartment($id)
    {
        $department = Department::find($id);
        if (!$department) {
            return null;
        }
        $department->status = 'Inactive';
        $department->deleted_by = auth()->id();
        $department->save();

        return $department;
    }
}

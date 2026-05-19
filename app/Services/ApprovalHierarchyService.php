<?php

namespace App\Services;

use App\Models\ApprovalLevel;
use Illuminate\Support\Facades\DB;
use App\Models\ApprovalHierarchy;
use Illuminate\Support\Facades\Hash;

class ApprovalHierarchyService
{
    public function getAllHierarchies()
    {
        return ApprovalHierarchy::with('levels.approver')->get();
    }

    public function getHierarchyById($id)
    {
        return ApprovalHierarchy::with('levels.approver')->find($id);
    }

    public function createHierarchy(array $data)
    {
        DB::beginTransaction();

        try {

            $hierarchy = ApprovalHierarchy::create([
                'department_id' => $data['department_id'],
                'name' => $data['name']
            ]);

            foreach ($data['levels'] as $level) {

                ApprovalLevel::create([
                    'approval_hierarchy_id' => $hierarchy->id,
                    'approver_id' => $level['approver_id'],
                    'level' => $level['level']
                ]);
            }

            DB::commit();

            return $hierarchy;

        } catch (\Throwable $e) {

            DB::rollBack();

            return false;
        }
    }

    public function updateHierarchy($id, array $data)
    {
        DB::beginTransaction();

        try {
            $hierarchy = $this->getHierarchyById($id);
            if (!$hierarchy) {
                return null;
            }

            $hierarchy->update([
                'department_id' => $data['department_id'],
                'name' => $data['name']
            ]);

            // Update levels
            $existingLevels = $hierarchy->levels->keyBy('id');
            foreach ($data['levels'] as $levelData) {
                if (isset($levelData['id']) && $existingLevels->has($levelData['id'])) {
                    $level = $existingLevels->get($levelData['id']);
                    $level->update([
                        'approver_id' => $levelData['approver_id'],
                        'level' => $levelData['level']
                    ]);
                    $existingLevels->forget($levelData['id']);
                } else {
                    ApprovalLevel::create([
                        'approval_hierarchy_id' => $hierarchy->id,
                        'approver_id' => $levelData['approver_id'],
                        'level' => $levelData['level']
                    ]);
                }
            }

            // Delete removed levels
            foreach ($existingLevels as $level) {
                $level->delete();
            }

            DB::commit();

            return $hierarchy;

        } catch (\Throwable $e) {

            DB::rollBack();

            return false;
        }
    }

    public function deleteHierarchy($id)
    {
        $hierarchy = $this->getHierarchyById($id);
        if (!$hierarchy) {
            return null;
        }

        DB::beginTransaction();

        try {
            $hierarchy->levels()->delete();
            $hierarchy->delete();
            
            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            return false;
        }
    }
}

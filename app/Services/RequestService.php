<?php

namespace App\Services;

use App\Models\RequestApproval;
use App\Models\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ApprovalHierarchy;
use Illuminate\Support\Facades\Hash;

class RequestService
{
    public function getAllRequests()
    {
        return Request::with('approvalHierarchy.levels.approver')
                ->where('user_id', auth()->id())->get();
    }

    public function getRequestById($id)
    {
        return Request::with('approvalHierarchy.levels.approver')->find($id);
    }

    public function createRequest(array $data)
    {
        DB::beginTransaction();

        try {

            $user = auth()->user();

            $workflowRequest = Request::create([
                'user_id' => $user->id,
                'department_id' => $user->department_id,
                'title' => $data['title'],
                'description' => $data['description'],
                'status' => 'pending',
                'current_level' => 1
            ]);

            $hierarchy = ApprovalHierarchy::where('department_id', $user->department_id)->first();

            foreach ($hierarchy->levels as $level) {

                RequestApproval::create([
                    'request_id' => $workflowRequest->id,
                    'approver_id' => $level->approver_id,
                    'level' => $level->level,
                    'status' => 'pending'
                ]);
            }

            DB::commit();

            return $workflowRequest;

        } catch (\Throwable $e) {

            DB::rollBack();

            return false;
        }
    }

    public function updateRequest($id, array $data)
    {
        $request = $this->getRequestById($id);
        if (!$request) {
            return null;
        }

        $request->update($data);

        return $request;
    }

    public function deleteRequest($id)
    {
        $request = $this->getRequestById($id);
        if (!$request) {
            return false;
        }

        return $request->delete();
    }
}

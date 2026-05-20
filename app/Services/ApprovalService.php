<?php

namespace App\Services;

use App\Models\ApprovalLevel;
use Illuminate\Support\Facades\DB;
use App\Models\RequestApproval;

class ApprovalService
{
    public function getAllRequests()
    {
        return RequestApproval::with(['request', 'approver'])
            ->where('approver_id', auth()->id())
            ->latest()
            ->get();
    }

    public function getAllPendingRequests()
    {
        return RequestApproval::with(['request', 'approver'])
            ->where('approver_id', auth()->id())
            ->where('status', 'pending')
            ->get();
    }

    public function getRequestById($id)
    {
        return RequestApproval::with(['request', 'approver'])->where('id', $id)
                        ->where('approver_id', auth()->id())
                        ->firstOrFail();
    }

    public function approve($approval)
    {
        DB::beginTransaction();

        try {

            $user = auth()->user();

            if ($approval->approver_id !== $user->id) {

                return response()->json([
                    'message' => 'Unauthorized'
                ], 403);
            }

            if ($approval->status !== 'pending') {

                return response()->json([
                    'message' => 'Approval already processed'
                ], 422);
            }

            $workflowRequest = $approval->request;

            if ($workflowRequest->status !== 'pending') {

                return response()->json([
                    'message' => 'Request already processed'
                ], 422);
            }

            if ($workflowRequest->current_level !== $approval->level) {
                return response()->json([
                    'message' => 'Not current approval level'
                ], 422);
            }

            $approval->update([
                'status' => 'approved',
                'acted_at' => now()
            ]);

            $nextApproval = RequestApproval::where('request_id',$workflowRequest->id)
                                ->where('level', '>', $approval->level)
                                ->where('status', 'pending')
                                ->orderBy('level')->first();

            if ($nextApproval) {
                $workflowRequest->update([
                    'current_level' => $nextApproval->level
                ]);

            } else {

                $workflowRequest->update([
                    'status' => 'approved'
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Approved successfully'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function reject($approval, $data)
    {
        DB::beginTransaction();

        try {

            $user = auth()->user();

            // Ensure only assigned approver can reject
            if ($approval->approver_id !== $user->id) {

                return response()->json([
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Ensure approval has not already been processed
            if ($approval->status !== 'pending') {

                return response()->json([
                    'message' => 'Approval already processed'
                ], 422);
            }

            $workflowRequest = $approval->request;

            if ($workflowRequest->status !== 'pending') {

                return response()->json([
                    'message' => 'Request already processed'
                ], 422);
            }

            if ($workflowRequest->current_level !== $approval->level) {

                return response()->json([
                    'message' => 'Not current approval level'
                ], 422);
            }

            $approval->update([
                'status' => 'rejected',
                'comment' => $data['comment'],
                'acted_at' => now()
            ]);

            $workflowRequest->update([
                'status' => 'rejected'
            ]);

            RequestApproval::where('request_id', $workflowRequest->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'rejected'
                ]);

            DB::commit();

            return response()->json([
                'message' => 'Request rejected'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

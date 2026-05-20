<?php

namespace App\Http\Controllers;

use App\Http\Requests\RejectRequest;
use App\Models\RequestApproval;
use App\Services\ApprovalService;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function __construct(protected ApprovalService $approvalService){}

    public function index()
    {
        $requests = $this->approvalService->getAllRequests();
        return response()->json($requests, 200);
    }

    public function getPendingRequest()
    {
        $requests = $this->approvalService->getAllPendingRequests();
        return response()->json($requests, 200);
    }

    public function show($id)
    {
        $request = $this->approvalService->getRequestById($id);

        if (!$request) {
            return response()->json(['message' => 'Request not found'], 404);
        }

        return response()->json($request, 200);
    }

    public function approve(RequestApproval $approval)
    {
        return $this->approvalService->approve($approval);
    }

    public function reject(RequestApproval $approval, RejectRequest $request)
    {
        return $this->approvalService->reject($approval, $request->validated());
    }
}

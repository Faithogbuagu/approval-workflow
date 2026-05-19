<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRequest;
use App\Services\RequestService;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function __construct(protected RequestService $requestService){}

    public function index()
    {
        $requests = $this->requestService->getAllRequests();
        return RequestResource::collection($requests, 200);
    }

    public function show($id)
    {
        $request = $this->requestService->getRequestById($id);

        if (!$request) {
            return response()->json(['message' => 'Request not found'], 404);
        }

        return new RequestResource($request, 200);
    }

    public function store(CreateRequest $request)
    {
        $newRequest = $this->requestService->createRequest($request->validated());

        if (!$newRequest) {
            return response()->json(['message' => 'Failed to create request'], 500);
        }

        return new RequestResource($newRequest, 201);
    }

    public function update(CreateRequest $request, $id)
    {
        $updatedRequest = $this->requestService->updateRequest($id, $request->validated());

        if (!$updatedRequest) {
            return response()->json(['message' => 'Request not found'], 404);
        }

        return new RequestResource($updatedRequest, 200);
    }

    public function destroy($id)
    {
        $deleted = $this->requestService->deleteRequest($id);

        if (!$deleted) {
            return response()->json(['message' => 'Request not found'], 404);
        }

        return response()->json(['message' => 'Request deleted successfully'], 200);
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'requester' => new UserResource($this->user),
            // 'department' => new DepartmentResource($this->department),
            'approvals' => ApprovalResource::collection($this->approvals),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

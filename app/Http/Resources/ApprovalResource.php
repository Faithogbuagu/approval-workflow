<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApprovalResource extends JsonResource
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
            'request_id' => $this->request_id,
            'approver_id' => $this->approver_id,
            'level' => $this->level,
            'status' => $this->status,
            'approver' => new UserResource($this->approver),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgramWaitingApprovalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type_id' => $this->type_id,
            'allocation_rate' => $this->allocation_rate,
            'status' => $this->status, 
            'created_at' => $this->created_at
        ];
    }
}

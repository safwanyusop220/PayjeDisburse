<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllocationResource extends JsonResource
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
            'allocation_source' => $this->allocation_source,
            'total_allocation' => $this->total_allocation,
            'allocation_balance' => $this->allocation_balance,
            'status' => $this->status,
            'reject_reason' => $this->reject_reason,
            'created_at' => $this->created_at,
            'account_number' => $this->account_number,
            'bank' => $this->bank,

        ];
    }
}

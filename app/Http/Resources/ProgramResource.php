<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgramResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'type_id'         => $this->type_id,
            'allocation_rate' => $this->allocation_rate,
            'status'          => $this->status,
            'payment_date'    => $this->payment_date,
            'total_month'     => $this->total_month,
            'total_year'      => $this->total_year,
            'end_date'        => $this->end_date,
            'latest_payment'  => $this->latest_payment,
            'created_at'      => $this->created_at,
            'allocation'      => $this->allocation,
            'frequency'       => $this->frequency,
        ];
    }
}

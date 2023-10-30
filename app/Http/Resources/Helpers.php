
<?php

use App\Http\Resources\AllocationResource;
use App\Models\Allocation;
use Inertia\Inertia;

function waitingApprovalIndex() {
    // Your custom logic here
    return Inertia::render('Admin/Allocations/WaitingApprovalIndex', [
        'allocations' => AllocationResource::collection(Allocation::all())
    ]);
}


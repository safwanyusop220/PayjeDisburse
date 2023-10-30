<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function approveAllocation($id)
    {
        $allocations = Allocation::find($id);

        $allocations->status='3';
        
        $allocations->save();

        return redirect()->back();
    }
}

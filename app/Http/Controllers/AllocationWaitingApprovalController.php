<?php

namespace App\Http\Controllers;

use App\Http\Resources\AllocationResource;
use App\Models\Allocation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AllocationWaitingApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Example condition: Get allocations with status equal to 1
        $allocations = Allocation::orderBy('id', 'desc')->where('status', 1)->paginate(8);

        return Inertia::render('Admin/Allocations/WaitingApprovalIndex', [
            'allocations' => $allocations
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

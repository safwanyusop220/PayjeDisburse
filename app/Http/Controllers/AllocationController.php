<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAllocationRequest;
use App\Http\Requests\UpdateAllocationRequest;
use App\Http\Resources\AllocationResource;
use App\Models\Allocation;
use App\Models\RefBank;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Inertia\Inertia;
use Inertia\Response;
use Inertia\Ssr\Response as SsrResponse;

class AllocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = FacadesRequest::input('perPage') ?: 5;
        return Inertia::render('Admin/Allocations/AllocationIndex', [
            'allocations' => Allocation::query()
                ->when(FacadesRequest::input('search'), function ($query, $search) {
                    $query->where('allocation_source', 'like', "%{$search}%");
                })
                ->orderBy('id', 'desc')
                ->with('bank', 'status_id')
                ->paginate($perPage)
                ->withQueryString(),

            'filters' => FacadesRequest::only(['search', 'perPage'])
        ]);
    }

    public function waitingApproval()
    {
        $perPage = FacadesRequest::input('perPage') ?: 5;
        return Inertia::render('Admin/Allocations/WaitingApprovalIndex', [
            'allocations' => Allocation::query()
            ->when(FacadesRequest::input('search'), function ($query, $search) {
                $query->where('allocation_source', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->with('status_id')
            ->where('status', 1)
            ->paginate($perPage),
            'filters' => FacadesRequest::only(['search', 'perPage'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $this->authorize('create', Allocation::class);
        return Inertia::render('Admin/Allocations/Create', [
            'refBanks' => RefBank::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAllocationRequest $request): RedirectResponse
    {
        $this->authorize('create', Allocation::class);
        Allocation::create($request->validated());

        return  to_route('allocations.index');
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
    public function edit(Allocation $allocation): Response
    {
        $this->authorize('update', $allocation);
        return Inertia::render('Admin/Allocations/Edit', [
            'allocation' => new AllocationResource($allocation),
            'refBanks' => RefBank::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateAllocationRequest $request, Allocation $allocation): RedirectResponse
    {

        $allocation->update([
            'allocation_source' => $request->allocation_source,
            'total_allocation' => $request->total_allocation,
            'allocation_balance' => $request->allocation_balance,
            'account_number' => $request->account_number,
            'bank' => $request->bank,

        ]);
        return to_route('allocations.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $allocation = Allocation::findOrFail($id);
        $allocation->delete();
        return back();
    }



    public function allocationApproval(int $id): Response
    {
        $allocation = Allocation::findOrFail($id);
        return Inertia::render('Admin/Allocations/AllocationApproval', [
            'allocation' => new AllocationResource($allocation),
        ]);
    }

    public function approve(int $id)
    {
        $single_allocations_id = explode(',', $id);

        foreach ($single_allocations_id as $id) {
            $allocation = Allocation::findOrFail($id);
            $allocation->update([
                'status' => Allocation::STATUS_APPROVE,
                'reject_reason' => '-'
            ]);
        }
    }

    public function reject(Request $request,  int $id)
    {
        $allocation = Allocation::find($id);
        $allocation->update([
            'status' => Allocation::STATUS_REJECT,
            'reject_reason' => $request->reject_reason
        ]);
    }
}

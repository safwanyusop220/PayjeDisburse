<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAllocationRequest;
use App\Http\Requests\UpdateAllocationRequest;
use App\Http\Resources\AllocationResource;
use App\Models\Allocation;
use App\Models\Program;
use App\Models\RefBank;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Inertia\Inertia;
use Inertia\Response;
use Inertia\Ssr\Response as SsrResponse;
use Illuminate\Support\Facades\Route;

class AllocationController extends Controller
{
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

    public function waitingRecommendation()
    {
        $this->authorize('waitingRecommendation', Allocation::class);

        $perPage = FacadesRequest::input('perPage') ?: 5;
        return Inertia::render('Admin/Allocations/WaitingRecommendation', [
            'allocations' => Allocation::query()
            ->when(FacadesRequest::input('search'), function ($query, $search) {
                $query->where('allocation_source', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->with('status_id', 'bank')
            ->where('status', 1)
            ->paginate($perPage)
            ->withQueryString(),
            'filters' => FacadesRequest::only(['search', 'perPage'])
        ]);
    }

    public function allocationRecommendation(int $id): Response
    {
        $allocation = Allocation::with('bank')->findOrFail($id);
        return Inertia::render('Admin/Allocations/AllocationRecommendation', [
            'allocation' => $allocation,
        ]);    
    }

    public function approveRecommendation(int $id)
    {
        $user = Auth::user();
        try {
            $single_allocations_id = explode(',', $id);
            foreach ($single_allocations_id as $id) {
                $allocation = Allocation::findOrFail($id);
                $allocation->update([
                    'status' => Allocation::STATUS_RECOMMEND,
                    'reject_reason' => '-'
                ]);
            }
            $user->log("Web/Allocation/Recommendation", "Recommend Allocation");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the allocation');
        }
    }

    public function rejectRecommendation(Request $request,  int $id)
    {
        $user = Auth::user();
        try {
            $single_allocations_id = explode(',', $id);
            foreach ($single_allocations_id as $id) {
                    $allocation = Allocation::find($id);
                    $allocation->update([
                    'status' => Allocation::STATUS_REJECT,
                    'reject_reason' => $request->reject_reason
                ]);
            }
            $user->log("Web/Allocation/Recommendation", "Reject Recommendation");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the allocation');
        }
    }

    public function waitingApproval()
    {
        $this->authorize('waitingApproval', Allocation::class);
        $perPage = FacadesRequest::input('perPage') ?: 5;
        return Inertia::render('Admin/Allocations/WaitingApprovalIndex', [
            'allocations' => Allocation::query()
            ->when(FacadesRequest::input('search'), function ($query, $search) {
                $query->where('allocation_source', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->with('status_id')
            ->where('status', 2)
            ->paginate($perPage)
            ->withQueryString(),
            'filters' => FacadesRequest::only(['search', 'perPage'])
        ]);
    }
    public function allocationApproval(int $id): Response
    {
        $allocation = Allocation::with('bank')->findOrFail($id);
        return Inertia::render('Admin/Allocations/AllocationApproval', [
            'allocation' => $allocation,
        ]);    
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Allocations/Create', [
            'refBanks' => RefBank::all(),
        ]);
    }

    public function store(CreateAllocationRequest $request): RedirectResponse
    {
        $user = Auth::user();

        try {
            $allocation = Allocation::create($request->validated());
            $user->log("Web/Allocation/Create", "Create Allocation");
            return  to_route('allocations.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the allocation');
        }
    }

    public function edit(Allocation $allocation): Response
    {
        $allocationID = $allocation->id;
        $programs = Program::where('allocation', $allocationID)->with('status_id')->get();
        return Inertia::render('Admin/Allocations/Edit', [
            'allocation' => new AllocationResource($allocation),
            'refBanks' => RefBank::all(),
            'programs' => $programs
        ]);
    }

    public function update(CreateAllocationRequest $request, Allocation $allocation): RedirectResponse
    {
        $user = Auth::user();

        try {
            $allocation->update([
                'allocation_source' => $request->allocation_source,
                'total_allocation' => $request->total_allocation,
                'allocation_balance' => $request->allocation_balance,
                'account_number' => $request->account_number,
                'bank' => $request->bank,
    
            ]);

            $user->log("Web/Allocation/Update", "Update Allocation");

            return  to_route('allocations.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the allocation');
        }

        
    }

    public function destroy(int $id)
    {
        $user = Auth::user();

        try {
            $allocation = Allocation::findOrFail($id);
            $allocation->delete();
            $user->log("Web/Allocation/Index", "Delete Allocation");
            return  to_route('allocations.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the allocation');
        }

        
    }
    
    public function approve(int $id)
    {
        $user = Auth::user();

        try {
            $single_allocations_id = explode(',', $id);

            foreach ($single_allocations_id as $id) {
                $allocation = Allocation::findOrFail($id);
                $allocation->update([
                    'status' => Allocation::STATUS_APPROVE,
                    'reject_reason' => '-'
                ]);
            }

            $user->log("Web/Allocation/Approval", "Approve Allocation");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the allocation');
        }
    }

    public function reject(Request $request,  int $id)
    {
        $user = Auth::user();

        try {
            $single_allocations_id = explode(',', $id);
        
            foreach ($single_allocations_id as $id) {
                    $allocation = Allocation::find($id);
                    $allocation->update([
                    'status' => Allocation::STATUS_REJECT,
                    'reject_reason' => $request->reject_reason
                ]);
            }
            $user->log("Web/Allocation/Approval", "Reject Allocation");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the allocation');
        }
    }

    //For Audit Trail
    // $user->log("INSERTED DATA for Allocation ID {$allocation->id}");
    // $modelInfo = "web/allocation/create";
    // $user->log("Performed some action", $modelInfo);
}

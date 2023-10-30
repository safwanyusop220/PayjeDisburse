<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReceiverRequest;
use App\Http\Resources\AllocationResource;
use App\Http\Resources\ProgramResource;
use App\Http\Resources\ProgramTypeResource;
use App\Http\Resources\ReceiverResource;
use App\Models\Allocation;
use App\Models\Payment;
use App\Models\Program;
use App\Models\ProgramType;
use App\Models\Receiver;
use App\Models\RefBank;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Inertia\Inertia;
use Inertia\Response;

class ReceiverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = FacadesRequest::input('perPage') ?: 5;

        return Inertia::render('Admin/Receivers/ReceiverIndex', [
            'receivers' => Receiver::query()
            ->when(FacadesRequest::input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->with('status_id', 'program', 'bank')
            ->paginate($perPage),

            'filters' => FacadesRequest::only(['search', 'perPage'])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $this->authorize('create', Receiver::class);
        return Inertia::render('Admin/Receivers/Create', [
            'programTypes' => ProgramTypeResource::collection(ProgramType::all()),
            'programs' => ProgramResource::collection(Program::where('status', 3)->get()),
            'receivers' => Receiver::with('program')->get(),
            'refBanks' => RefBank::all(),
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateReceiverRequest $request): RedirectResponse
    {
        $this->authorize('create', Receiver::class);
        Receiver::create($request->validated());
        return  to_route('receivers.index');
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
    public function edit(Receiver $receiver): Response
    {
        $this->authorize('update', $receiver);
        return Inertia::render('Admin/Receivers/Edit', [
            'receiver' => new ReceiverResource($receiver),
            'programs' => ProgramResource::collection(Program::all()),
            'allocations' => AllocationResource::collection(Allocation::all()),
            'refBanks' => RefBank::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateReceiverRequest $request, Receiver $receiver): RedirectResponse
    {
        $receiver->update([
            'name'                  => $request->name,
            'identification_number' => $request->identification_number,
            'address'               => $request->address,
            'phone_number'          => $request->phone_number,
            'email'                 => $request->email,
            'program_id'            => $request->program_id,
            'bank'                  => $request->bank,
            'account_number'        => $request->account_number,
        ]);
        return to_route('receivers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $receiver = Receiver::findOrFail($id);
        $receiver->delete();
        return back();
    }

    public function waitingApproval()
    {
        $perPage = FacadesRequest::input('perPage') ?: 5;
        return Inertia::render('Admin/Receivers/WaitingApprovalIndex', [
            'receivers' => Receiver::query()
            ->when(FacadesRequest::input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->with('status_id', 'program', 'bank')
            ->where('status', 1)
            ->paginate($perPage),

            'filters' => FacadesRequest::only(['search', 'perPage'])
        ]);
    }
    // Go to Allocation Approval Page
    public function receiverApproval(int $id): Response
    {
        $program1Types = Program::where('type_id', 1)->get();

        $receiver = Receiver::findOrFail($id);
        return Inertia::render('Admin/Receivers/ReceiverApproval', [
            'receiver' => new ReceiverResource($receiver),
            'program1Types' => ProgramResource::collection($program1Types),

            'programs' => ProgramResource::collection(Program::all()),
            'allocations' => AllocationResource::collection(Allocation::all()),
            'refBanks' => RefBank::all(),
        ]);
    }

    public function approve($id)
    {
        $single_receiver_id = explode(',', $id);

        foreach ($single_receiver_id as $id) {
            $receiver = Receiver::findOrFail($id);
            $receiver->update([
                'status' => Receiver::STATUS_APPROVE,
                'reject_reason' => '-'
            ]);

            if ($receiver->program->type_id == 1) {
                Payment::create([
                    'receiver_id' => $receiver->id,
                    'total_receiver' => 1,
                    'status' => 1,
                ]);
            } else {

                $programId = $receiver->program_id;
                $payment = Payment::where('program_id', $programId)->first();

                if ($payment) {
                    $payment->increment('total_receiver');
                } else {
                    // Handle the case when no matching Payment record is found
                    // You can put your code here for the "else" case
                }
            }
        }
    }

    public function reject(Request $request,  int $id)
    {
        $receiver = Receiver::find($id);
        $receiver->update([
            'status' => Receiver::STATUS_REJECT,
            'reject_reason' => $request->reject_reason
        ]);
    }
}

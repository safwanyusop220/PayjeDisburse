<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateReceiverRequest;
use App\Http\Resources\AllocationResource;
use App\Http\Resources\ProgramResource;
use App\Http\Resources\ProgramTypeResource;
use App\Http\Resources\ReceiverResource;
use App\Imports\ReceiverImport;
use App\Models\Allocation;
use App\Models\Payment;
use App\Models\Program;
use App\Models\ProgramType;
use App\Models\Receiver;
use App\Models\RefBank;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;

class ReceiverController extends Controller
{
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
            ->paginate($perPage)
            ->withQueryString(),
            'filters' => FacadesRequest::only(['search', 'perPage']),
            'programs' => ProgramResource::collection(Program::where('status', 3)->get()),

        ]);
    }

    public function create(): Response
    {
        //$this->authorize('create', Receiver::class);
        return Inertia::render('Admin/Receivers/Create', [
            'programTypes' => ProgramTypeResource::collection(ProgramType::all()),
            'programs' => ProgramResource::collection(Program::where('status', 3)->get()),
            'receivers' => Receiver::with('program')->get(),
            'refBanks' => RefBank::all(),
        ]);
    }

    public function store(CreateReceiverRequest $request): RedirectResponse
    {
        $user = Auth::user();

        try {
            Receiver::create($request->validated());
            $user->log("Web/Receiver/Create", "Create Receiver");
            return  to_route('receivers.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the Receiver');
        }
    }

    public function edit(Receiver $receiver): Response
    {
        //$this->authorize('update', $receiver);
        return Inertia::render('Admin/Receivers/Edit', [
            'receiver' => new ReceiverResource($receiver),
            'programs' => ProgramResource::collection(Program::all()),
            'allocations' => AllocationResource::collection(Allocation::all()),
            'refBanks' => RefBank::all(),
        ]);
    }

    public function update(CreateReceiverRequest $request, Receiver $receiver): RedirectResponse
    {
        $user = Auth::user();

        try {
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
            $user->log("Web/Receiver/Update", "Update Receiver");
            return to_route('receivers.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the Receiver');
        }
    }

    public function destroy(int $id)
    {
        $user = Auth::user();

        try {
            $receiver = Receiver::findOrFail($id);
            $receiver->delete();
            $user->log("Web/Receiver/Index", "Delete Receiver");

            return to_route('receivers.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the Receiver');
        }
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
            ->where('status', 2)
            ->paginate($perPage)
            ->withQueryString(),
            'filters' => FacadesRequest::only(['search', 'perPage'])
        ]);
    }

    public function waitingRecommendation()
    {
        $perPage = FacadesRequest::input('perPage') ?: 5;
        return Inertia::render('Admin/Receivers/WaitingRecommendationIndex', [
            'receivers' => Receiver::query()
            ->when(FacadesRequest::input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->with('status_id', 'program', 'bank')
            ->where('status', 1)
            ->paginate($perPage)
            ->withQueryString(),
            'filters' => FacadesRequest::only(['search', 'perPage'])
        ]);
    }

    public function receiverRecommendation(int $id): Response
    {
        $program1Types = Program::where('type_id', 1)->get();

        $receiver = Receiver::findOrFail($id);
        return Inertia::render('Admin/Receivers/ReceiverRecommendation', [
            'receiver' => new ReceiverResource($receiver),
            'program1Types' => ProgramResource::collection($program1Types),

            'programs' => ProgramResource::collection(Program::all()),
            'allocations' => AllocationResource::collection(Allocation::all()),
            'refBanks' => RefBank::all(),
        ]);
    }

    public function approveRecommendation($id)
    {
        $user = Auth::user();

        try {
            $single_receiver_id = explode(',', $id);

            foreach ($single_receiver_id as $id) {
                $receiver = Receiver::findOrFail($id);
                $receiver->update([
                    'status' => Receiver::STATUS_RECOMMENDATION,
                    'reject_reason' => '-'
                ]);
            }
            $user->log("Web/Receiver/Recommendation", "Accept Recommendation");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the Receiver');
        }

    }
    
    public function rejectRecommendation(Request $request,  int $id)
    {
        $user = Auth::user();

        try {
            $single_program_id = explode(',', $id);

            foreach ($single_program_id as $id) {
            
                $receiver = Receiver::find($id);
                $receiver->update([
                    'status' => Receiver::STATUS_REJECT,
                    'reject_reason' => $request->reject_reason
                ]);
            }

            $user->log("Web/Receiver/Recommendation", "Reject Recommendation");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the Receiver');
        }
    }
    
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
        $user = Auth::user();

        try {
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
                        'status_id' => Payment::STATUS_REQUEST,
                        'payment_date' => $receiver->program->payment_date
                    ]);
                } else {
    
                    $programId = $receiver->program_id;
                    $payment = Payment::where('program_id', $programId)->first();
    
                    if ($payment) {
                        $payment->increment('total_receiver');
                    } else {
                    }
                }
            }

            $user->log("Web/Receiver/Approval", "Approve Receiver");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the Receiver');
        }
    }

    public function reject(Request $request,  int $id)
    {
        $user = Auth::user();

        try {
            $single_program_id = explode(',', $id);

            foreach ($single_program_id as $id) {
                $receiver = Receiver::find($id);
                $receiver->update([
                    'status' => Receiver::STATUS_REJECT,
                    'reject_reason' => $request->reject_reason
                ]);
            }
            $user->log("Web/Receiver/Approval", "Reject Receiver");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the Receiver');
        }

    }

    public function import(Request $request)
    {
        $selectedProgramId = $request->input('program_id');

        $user = Auth::user();

        try {
            if ($request->hasFile('importFile')) {
                $path = $request->file('importFile')->getRealPath();
                $import = new ReceiverImport($selectedProgramId);
                Excel::import($import, $path);
            }
    
            $user->log("Web/Receiver/Index", "Import Receiver");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the Receiver');
        }


        // $path = $request->file('safd')->getRealPath();
        // Excel::import(new ReceiverImport, $path);
    }
}

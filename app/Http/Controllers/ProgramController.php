<?php

namespace App\Http\Controllers;

use App\Exports\PaymentsExport;
use App\Http\Requests\CreateInstallmentRequest;
use App\Http\Requests\CreateProgramRequest;
use App\Http\Resources\AllocationResource;
use App\Http\Resources\ProgramResource;
use App\Http\Resources\ProgramTypeResource;
use App\Models\Allocation;
use App\Models\InstallmentPrograms;
use App\Models\Payment;
use App\Models\Program;
use App\Models\ProgramType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Mockery\Instantiator;

class ProgramController extends Controller
{
    public function index()
    {
        $perPage = FacadesRequest::input('perPage') ?: 5;
        return Inertia::render('Admin/Programs/ProgramIndex', [
            'programs' => Program::query()
            ->when(FacadesRequest::input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->with('status_id')
            ->paginate($perPage)
            ->withQueryString(),

            'filters' => FacadesRequest::only(['search', 'perPage'])
        ]);
    }

    public function waitingRecommendation()
    {
        $perPage = FacadesRequest::input('perPage') ?: 5;
        return Inertia::render('Admin/Programs/WaitingRecommendationIndex', [
            'programs' => Program::query()
                ->when(FacadesRequest::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->orderBy('id', 'desc')
                ->with('status_id')
                ->where('status', 1)
                ->paginate($perPage)
                ->withQueryString(),
                'filters' => FacadesRequest::only(['search', 'perPage'])

        ]);
    }

    public function programRecommendation(int $id): Response
    {
        $program = Program::with('allocation_id', 'program_type')->findOrFail($id);
        $installmentPrograms = InstallmentPrograms::where('program_id', $id)->get();

        return Inertia::render('Admin/Programs/ProgramRecommendation', [
            'program' => $program,
            'installmentPrograms' => $installmentPrograms
        ]);    
    }

    public function approveRecommendation(int $id, Request $request)
    {
        $user = Auth::user();

        try {
            $single_program_id = explode(',', $id);

            foreach ($single_program_id as $id) {
                $program = Program::findOrFail($id);
                $program->update([
                    'status' => Program::STATUS_RECOMMENDED,
                    'reject_reason' => '-'
                ]);
            }

            $user->log("Web/Program/Recommendation", "Accept Recommendation");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the program');
        }
    }

    public function rejectRecommendation(Request $request,  int $id)
    {
        $user = Auth::user();

        try {
            $single_program_id = explode(',', $id);

            foreach ($single_program_id as $id) {
                    $program = Program::find($id);
                    $program->update([
                    'status' => Program::STATUS_REJECT,
                    'reject_reason' => $request->reject_reason
                ]);
            }
            $user->log("Web/Program/Recommendation", "Reject Recommendation");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the program');
        }
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Programs/Create', [
            'programTypes' => ProgramTypeResource::collection(ProgramType::all()),
            'allocations' => AllocationResource::collection(Allocation::where('status', 3)->get()),
        ]);
    }

    public function store(CreateProgramRequest $requestProgram, CreateInstallmentRequest $requestInstallment): RedirectResponse
    {
        $totalAmount = 0;
        $user = Auth::user();

        try {
            $program = new Program($requestProgram->validated());
            $program->save();
            $programId = $program->id;
    
            $data = $requestInstallment->only(['amount', 'installment_payment_date', 'allocation_rate', 'installment_name',  'type_id']);
    
            foreach ($data['amount'] as $index => $amount) {
                InstallmentPrograms::create([
                    'program_id' => $programId,
                    'installment_payment_date' => $data['installment_payment_date'][$index],
                    'installment_name' => $data['installment_name'][$index],
                    'amount' => $amount,
                ]);
            }

            $user->log("Web/Program/Create", "Create Program");
            return  to_route('programs.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the program');
        }
    }

    public function storeInstallment(CreateInstallmentRequest $request): RedirectResponse
    {
        $totalAmount = 0;
        $programId = $request->get('program_id');
        $data = $request->only(['amount', 'installment_payment_date', 'allocation_rate', 'installment_name',  'type_id']);

        if ($data['type_id'] == 3) {
            foreach ($data['amount'] as $index => $amount) {
                $totalAmount += $amount;
            }
        }

        if ($data['type_id'] == 3 && $totalAmount !== $data['allocation_rate']) {
            return redirect()->route('programs.installment');
        }

        foreach ($data['amount'] as $index => $amount) {
            InstallmentPrograms::create([
                'program_id' => $programId,
                'amount' => $amount,
                'installment_payment_date' => $data['installment_payment_date'][$index],
                'installment_name' => $data['installment_name'][$index]
            ]);

            $program = Program::findOrFail($programId);
            $program->update([
                'installment_status' => '2'
            ]);
        }
        return redirect()->route('programs.installment');
    }

    public function edit(Program $program): Response
    {
        //$this->authorize('update', $program);
        return Inertia::render('Admin/Programs/Edit', [
            'program' => new ProgramResource($program),
            'allocations' => AllocationResource::collection(Allocation::all())
        ]);
    }

    public function update(CreateProgramRequest $request, Program $program): RedirectResponse
    {
        $user = Auth::user();

        try {
            $program->update([
                'name'           => $request->name,
                'type_id'        => $request->type_id,
                'allocation'  => $request->allocation,
                'allocation_rate' => $request->allocation_rate,
                'latest_payment' => $request->latest_payment,
                'valid_until'    => $request->valid_until,
                'payment_date'   => $request->payment_date,
                'frequency'      => $request->frequency,
                'status'         => $request->status,
            ]);
            $user->log("Web/Program/Update", "Update Program");
            return to_route('programs.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the program');
        }


    }

    public function destroy(int $id)
    {
        $user = Auth::user();

        try {
            $program = Program::findOrFail($id);
            $program->delete();
            $user->log("Web/Program/Index", "Delete Program");
            return to_route('programs.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the program');
        }

    }

    public function programApproval(int $id): Response
    {
        $program = Program::with('allocation_id', 'program_type')->findOrFail($id);
        $installmentPrograms = InstallmentPrograms::where('program_id', $id)->get();
        //dd($installmentPrograms);

        return Inertia::render('Admin/Programs/ProgramApproval', [
            'program' => $program,
            'installmentPrograms' => $installmentPrograms
        ]);
    }

    public function waitingApproval()
    {
        $perPage = FacadesRequest::input('perPage') ?: 5;
        return Inertia::render('Admin/Programs/WaitingApprovalIndex', [
            'programs' => Program::query()
                ->when(FacadesRequest::input('search'), function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->orderBy('id', 'desc')
                ->with('status_id')
                ->where('status', 2)
                ->paginate($perPage)
                ->withQueryString(),
                'filters' => FacadesRequest::only(['search', 'perPage'])

        ]);
    }

    public function programInstallment()
    {
        return Inertia::render('Admin/Programs/ProgramInstallmentIndex', [
            'programs' => Program::orderBy('id', 'desc')
                ->with('status_id')
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where('type_id', 3)
                            ->orWhere('type_id', 4);
                    })
                        ->where('installment_status', 1);
                })
                ->paginate(8)
        ]);
    }

    public function createInstallment(int $id)
    {
        $program = Program::with('allocation_id')->find($id);
        return Inertia::render('Admin/Programs/CreateInstallment', [
            'program' => $program
        ]);
    }

    public function approve(int $id, Request $request)
    {
        $user = Auth::user();

        try {
            $single_program_id = explode(',', $id);

            foreach ($single_program_id as $id) {
                $program = Program::findOrFail($id);
                $totalReceiverCount = $program->receivers->where('status', 3)->count();
                $program->update([
                    'status' => Program::STATUS_APPROVE,
                    'reject_reason' => '-'
                ]);
    
                if ($program->type_id !== 1) {
                    Payment::create([
                        'program_id' => $program->id,
                        'total_receiver' => $totalReceiverCount,
                        'status_id' => Payment::STATUS_REQUEST,
                        'payment_date' => $program->payment_date
                    ]);
                }
            }

            $user->log("Web/Program/Approval", "Approve Program");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the program');
        }
    }

    public function reject(Request $request,  int $id)
    {
        $user = Auth::user();

        try {
            $single_program_id = explode(',', $id);

            foreach ($single_program_id as $id) {
                    $program = Program::find($id);
                    $program->update([
                    'status' => Program::STATUS_REJECT,
                    'reject_reason' => $request->reject_reason
                ]);
            }
            $user->log("Web/Program/Approval", "Reject Program");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the program');
        }
    }

    public function export()
    {
        dd('test');
        return Excel::download(new PaymentsExport, 'payments-data.xlsx');
    }
}

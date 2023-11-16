<?php

namespace App\Http\Controllers;

use App\Exports\PaymentsDataExport;
use App\Exports\PaymentsExport;
use App\Imports\PaymentsImport;
use App\Models\Payment;
use App\Models\Receiver;
use App\Models\Program;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requiredProcessPaymentCount = Receiver::with('program')->where('program_id', 23)->where('status', 3)->count();
        $allProgram = Program::all();

        foreach ($allProgram as $program) {
            $program['requiredProcessPaymentsCount'] = $program->receivers->count();
        }

        $requestPayment = Payment::orderBy('id', 'desc')->with('program', 'receiver')->where('status_id', 5)->paginate(9);
        $requestProcessing = Payment::orderBy('id', 'desc')->with('program', 'receiver')->where('status_id', 6)->paginate(9);
        $requestProceed = Payment::orderBy('id', 'desc')->with('program', 'receiver')->where('status_id', 7)->paginate(9);

        return Inertia::render('Admin/Payments/PaymentIndex', [
            'requestPayments' => $requestPayment,
            'requestProcessings' => $requestProcessing,
            'requestProceeds' => $requestProceed,
        ]);
    }

    public function programReceiverList($program_id)
    {
        $receivers = Receiver::with('program', 'bank')
            ->where('program_id', $program_id)
            ->where('status', 3)
            ->orderBy('id', 'desc')
            ->paginate(9);
        return Inertia::render('Admin/Payments/ProgramReceiverList', [
            'receivers' => $receivers,
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
    public function show($id)
    {
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

    public function export()
    {
        //dd('test');

        return Excel::download(new PaymentsExport, 'payments-data.csv');
        //return Excel::download(Payment::all(), 'paymentsdata.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate(['payments' => ['required']]);
        Excel::import(new PaymentsImport, $request->file('select_users_file'));

        return redirect('/');
    }
}

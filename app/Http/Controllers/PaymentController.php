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

        // $allReceiver->getCollection()->transform(function ($allReceiver) {
        //     $allReceiver['requiredProcessPaymentsCount'] = $allReceiver->receivers->count();
        // });

        foreach ($allProgram as $program) {
            $program['requiredProcessPaymentsCount'] = $program->receivers->count();
        }


        return Inertia::render('Admin/Payments/PaymentIndex', [
            'requiredProcessPayments' => Payment::all(),
            'requiredProcessPayments' => Payment::orderBy('id', 'desc')->with('program', 'receiver')->where('status', 1)->paginate(9),
            'inProgressPayments'      => Payment::orderBy('id', 'desc')->with('program', 'receiver')->where('status', 2)->paginate(9),
            'successTransaction'      => Payment::orderBy('id', 'desc')->with('program', 'receiver')->where('status', 3)->paginate(9),
            'failedTransaction'       => Payment::orderBy('id', 'desc')->with('program', 'receiver')->where('status', 4)->paginate(9),
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

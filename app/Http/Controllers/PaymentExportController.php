<?php

namespace App\Http\Controllers;

use App\Exports\PaymentsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PaymentExportController extends Controller
{
    public function export()
    {
        //dd('test');

        return Excel::download(new PaymentsExport, 'payments-data.xls');
        //return Excel::download(Payment::all(), 'paymentsdata.xlsx');
    }
}

<?php

namespace App\Imports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\ToModel;

class PaymentsImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Payment([
            'program_id'     => $row[1],
            'total_receiver' => $row[3],
            'payment_date'   => $row[4],
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Program;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $paymentDate = now();

        // $paymentDate = now()->format('d/m/Y');
        $end_date = Carbon::parse($paymentDate)->addMonths(1)->format('d/m/Y');


        Program::create(['name' => 'BKM',  'allocation' => '8', 'type_id' => '1', 'allocation_rate' => '200.00', 'payment_date' => $paymentDate, 'status' => '3']);
        Program::create(['name' => 'STR',  'allocation' => '7', 'type_id' => '2', 'allocation_rate' => '100.00', 'payment_date' => $paymentDate, 'total_month' => 2 , 'frequency' => '1', 'status' => '3']);
        Program::create(['name' => 'BRIM',  'allocation' => '8', 'type_id' => '3', 'allocation_rate' => '500.00', 'payment_date' => $paymentDate, 'status' => '3']);
        Program::create(['name' => 'E-KASIH',  'allocation' => '7', 'type_id' => '3', 'allocation_rate' => '900.00', 'payment_date' => $paymentDate, 'status' => '3']);
    }
}

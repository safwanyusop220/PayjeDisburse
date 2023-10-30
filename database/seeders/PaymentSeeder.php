<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Payment::create(['program_id' => '2', 'total_receiver' => '100', 'status' => '1']);
        Payment::create(['program_id' => '1', 'total_receiver' => '20', 'status' => '2']);
        Payment::create(['program_id' => '3', 'total_receiver' => '20', 'status' => '3']);
        Payment::create(['program_id' => '4', 'receiver_id' => '20', 'status' => '4']);
        Payment::create(['program_id' => '4', 'receiver_id' => '20', 'status' => '2']);
    }
}

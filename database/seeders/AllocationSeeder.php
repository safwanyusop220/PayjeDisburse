<?php

namespace Database\Seeders;

use App\Models\Allocation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AllocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Allocation::create(['allocation_source' => 'Yayasan Fazlan', 'total_allocation' => '950000.00', 'allocation_balance' => '600000.00',  'account_number' => '161024561113', 'bank' => '3']);

        Allocation::create(['allocation_source' => 'Zakat Melaka', 'total_allocation' => '550000.00', 'allocation_balance' => '246000.00', 'status' => '2', 'account_number' => '161224562531', 'bank' => '3']);

        Allocation::create(['allocation_source' => 'Kerajaan Melaka', 'total_allocation' => '550000.00', 'allocation_balance' => '246000.00', 'account_number' => '161224562531', 'bank' => '3']);

        Allocation::create(['allocation_source' => 'Bantuan Asnaf', 'total_allocation' => '550000.00', 'allocation_balance' => '246000.00', 'status' => '2', 'account_number' => '161224562531', 'bank' => '3']);

        Allocation::create(['allocation_source' => 'Baitumal Melaka', 'total_allocation' => '550000.00', 'allocation_balance' => '246000.00', 'status' => '2', 'account_number' => '161224562531', 'bank' => '3']);


        Allocation::create(['allocation_source' => 'Wakaf Melaka', 'total_allocation' => '550000.00', 'allocation_balance' => '246000.00', 'account_number' => '161224562531', 'bank' => '3']);


        Allocation::create(['allocation_source' => 'Maij', 'total_allocation' => '550000.00', 'allocation_balance' => '246000.00', 'status' => '3', 'account_number' => '161224562531', 'bank' => '3']);

        Allocation::create(['allocation_source' => 'Wakaf Melaka', 'total_allocation' => '550000.00', 'allocation_balance' => '246000.00', 'status' => '3', 'account_number' => '161224562531', 'bank' => '3']);


        Allocation::create(['allocation_source' => 'Yayasan Fazlan', 'total_allocation' => '550000.00', 'allocation_balance' => '246000.00', 'account_number' => '161224562531', 'bank' => '3']);
    }
}

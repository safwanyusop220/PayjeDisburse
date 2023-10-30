<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Program::create(['name' => 'A',  'allocation' => '4', 'type_id' => '1', 'allocation_rate' => '200.00', 'status' => '1']);
        Program::create(['name' => 'B',  'allocation' => '3', 'type_id' => '2', 'allocation_rate' => '100.00', 'status' => '2']);
        Program::create(['name' => 'C',  'allocation' => '2', 'type_id' => '3', 'allocation_rate' => '500.00', 'status' => '1']);
        Program::create(['name' => 'D',  'allocation' => '1', 'type_id' => '4', 'allocation_rate' => '900.00', 'status' => '3']);
        Program::factory(30)->create();
    }
}

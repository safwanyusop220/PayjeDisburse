<?php

namespace Database\Seeders;

use App\Models\Receiver;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReceiverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Receiver::create([
            'program_id' => '4',
            'status' => '1',
            'name' => 'Abdillah Safwan',
            'identification_number' => '000831-13-8300',
            'address' => 'A-20-13A, Landmark Residence, Kajang',
            'phone_number' => '013-8469671',
            'email' => 'safwanyusop220@gmail.com',
            'bank' => '2',
            'account_number' => '161024581113'
        ]);
        Receiver::create([
            'program_id' => '1',
            'status' => '1',
            'name' => 'Ammar',
            'identification_number' => '000431-04-1232',
            'address' => 'A-20-13A, Landmark Residence, Kajang',
            'phone_number' => '013-8469671',
            'email' => 'ammar220@gmail.com',
            'bank' => '3',
            'account_number' => '161024545162'
        ]);
        Receiver::factory(50)->create();
    }
}

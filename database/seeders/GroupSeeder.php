<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Group::create(['name' => 'System Role']);
        Group::create(['name' => 'System User']);
        Group::create(['name' => 'Allocation Module']);
        Group::create(['name' => 'Program Module']);
        Group::create(['name' => 'Receiver Module']);
        Group::create(['name' => 'Payment Module']);
    }
}

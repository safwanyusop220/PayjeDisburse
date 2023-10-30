<?php

namespace Database\Seeders;

use App\Models\ProgramType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProgramType::create(['name' => 'Individu']);
        ProgramType::create(['name' => 'Kumpulan']);
        ProgramType::create(['name' => 'Pecahan']);
        ProgramType::create(['name' => 'Kelompok']);


    }
}

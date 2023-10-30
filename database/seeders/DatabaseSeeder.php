<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(RoleSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(ReferenceBankSeeder::class);
        $this->call(StatusSeeder::class);
        $this->call(AllocationSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(ProgramTypeSeeder::class);
        //$this->call(ProgramSeeder::class);
        //$this->call(ReceiverSeeder::class);
        //$this->call(PaymentSeeder::class);

        //$this->call(ClassesSeeder::class);
        //$this->call(SectionsSeeder::class);
        //$this->call(StudentsSeeder::class);
    }
}

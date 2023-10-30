<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Allocation Module 
        Permission::create(['name' => 'create allocation']);
        Permission::create(['name' => 'update allocation']);
        // Program Module 
        Permission::create(['name' => 'create program']);
        Permission::create(['name' => 'update program']);
        // Receiver Module 
        Permission::create(['name' => 'create receiver']);
        Permission::create(['name' => 'update receiver']);


        $adminRole = Role::findByName('admin');

        $adminRole->givePermissionTo('create allocation');
        $adminRole->givePermissionTo('update allocation');
        $adminRole->givePermissionTo('create program');
        $adminRole->givePermissionTo('update program');
        $adminRole->givePermissionTo('create receiver');
        $adminRole->givePermissionTo('update receiver');
    }
}

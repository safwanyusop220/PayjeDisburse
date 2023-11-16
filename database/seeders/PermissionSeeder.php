<?php

namespace Database\Seeders;

use App\Models\Group;
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
        $groupRole = 'System Role';
        $group1 = Group::where('name', $groupRole)->first();

        $groupUser = 'System User';
        $group2 = Group::where('name', $groupUser)->first();

        $groupAllocation = 'Allocation Module';
        $group3 = Group::where('name', $groupAllocation)->first();

        $groupProgram = 'Program Module';
        $group4 = Group::where('name', $groupProgram)->first();

        $groupReceiver = 'Receiver Module';
        $group5 = Group::where('name', $groupReceiver)->first();

        $groupPayment = 'Payment Module';
        $group6 = Group::where('name', $groupPayment)->first();

        // Role Module 
        Permission::create(['name' => 'View System Role',     'group_name' => $group1->id]);
        Permission::create(['name' => 'Add System Role',      'group_name' => $group1->id]);
        Permission::create(['name' => 'Update System Role',    'group_name' => $group1->id]);
        Permission::create(['name' => 'Delete System Role',    'group_name' => $group1->id]);

        // User Module 
        Permission::create(['name' => 'View System User',     'group_name' => $group2->id]);
        Permission::create(['name' => 'Add System User',      'group_name' => $group2->id]);
        Permission::create(['name' => 'Update System User',    'group_name' => $group2->id]);
        Permission::create(['name' => 'Delete System User',    'group_name' => $group2->id]);

        // Allocation Module 
        Permission::create(['name' => 'View Allocation',  'group_name' => $group3->id]);
        Permission::create(['name' => 'Add Allocation',   'group_name' => $group3->id]);
        Permission::create(['name' => 'Allocation Recommendation', 'group_name' => $group3->id]);
        Permission::create(['name' => 'Allocation Approval',       'group_name' => $group3->id]);

        // Program Module 
        Permission::create(['name' => 'View Program',  'group_name' => $group4->id]);
        Permission::create(['name' => 'Add Program',   'group_name' => $group4->id]);
        Permission::create(['name' => 'Program Recommendation', 'group_name' => $group4->id]);
        Permission::create(['name' => 'Program Approval',       'group_name' => $group4->id]);

        // Receiver Module 
        Permission::create(['name' => 'View Receiver',  'group_name' => $group5->id]);
        Permission::create(['name' => 'Add Receiver',   'group_name' => $group5->id]);
        Permission::create(['name' => 'Receiver Recommendation',  'group_name' => $group5->id]);
        Permission::create(['name' => 'Receiver Approval',        'group_name' => $group5->id]);

        // Payment Module 
        Permission::create(['name' => 'View Payment',  'group_name' => $group6->id]);
        Permission::create(['name' => 'Add Payment',   'group_name' => $group6->id]);
        Permission::create(['name' => 'Payment recommendation',  'group_name' => $group6->id]);
        Permission::create(['name' => 'Payment approval',        'group_name' => $group6->id]);

        $adminRole = Role::findByName('Super Admin');
        $adminRole->givePermissionTo('Allocation Recommendation');
        $adminRole->givePermissionTo('Allocation Approval');

        $adminRole->givePermissionTo('Program Recommendation');
        $adminRole->givePermissionTo('Program Approval');

        $adminRole->givePermissionTo('Receiver Recommendation');
        $adminRole->givePermissionTo('Receiver Approval');

        $recommenderRole = Role::findByName('Recommender');
        $recommenderRole->givePermissionTo('Allocation Recommendation');
        $recommenderRole->givePermissionTo('Program Recommendation');
        $recommenderRole->givePermissionTo('Receiver Recommendation');


        $ApproverRole = Role::findByName('Approver');
        $ApproverRole->givePermissionTo('Allocation Approval');
        $ApproverRole->givePermissionTo('Program Approval');
        $ApproverRole->givePermissionTo('Receiver Approval');

    }
}

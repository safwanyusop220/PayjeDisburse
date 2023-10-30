<?php

namespace App\Http\Controllers;

use App\Models\Allocation;
use App\Models\Program;
use App\Models\Receiver;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $totalAllocations = Allocation::count(); 

        $programs = Program::count();
        $newPrograms = Program::where('status', 1)->count();


        $receivers = Receiver::count();
        $newReceivers = Receiver::where('status', 1)->count();
        
        return Inertia::render('Admin/Dashboards/DashboardIndex', [
            'totalAllocations' => $totalAllocations, 
            'receivers' => $receivers,
            'newReceivers' => $newReceivers,
            'programs' => $programs,
            'newPrograms' => $newPrograms,

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

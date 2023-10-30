<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProgramResource;
use App\Models\Program;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProgramWaitingApprovalController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $programs = Program::where('status', 1)->get();

        return Inertia::render('Admin/Programs/WaitingApprovalIndex', [
            'programs' => Program::orderBy('id', 'desc')->where('status', 1)->paginate(8)
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

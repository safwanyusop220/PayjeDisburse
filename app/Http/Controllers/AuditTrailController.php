<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Inertia\Inertia;

class AuditTrailController extends Controller
{
    public function index()
    {
        $perPage = FacadesRequest::input('perPage') ?: 5;

        return Inertia::render('Admin/AuditTrails/AuditTrailsIndex', [
            'auditTrails' => AuditTrail::query()
                ->when(FacadesRequest::input('search'), fn ($query, $search) =>
                    $query->where('name', 'like', "%{$search}%")
                )
                ->with('user')
                ->orderByDesc('id')
                ->paginate($perPage)
                ->withQueryString(),
            'filters' => FacadesRequest::only(['search', 'perPage']),
        ]);
    }

}

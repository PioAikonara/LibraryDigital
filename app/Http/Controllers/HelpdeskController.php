<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HelpdeskController extends Controller
{
    public function index()
    {
        $complaints = Complaint::with(['user', 'handler'])
            ->latest()
            ->paginate(15);
            
        return view('petugas.helpdesk.index', compact('complaints'));
    }

    public function respond(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'response' => 'required|string|max:1000'
        ]);

        $complaint->update([
            'status' => 'resolved',
            'response' => $validated['response'],
            'handled_by' => Auth::id(),
            'resolved_at' => now()
        ]);

        return back()->with('success', 'Keluhan telah ditanggapi dan diselesaikan.');
    }
}
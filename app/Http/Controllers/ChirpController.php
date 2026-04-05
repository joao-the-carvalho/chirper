<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chirps = Chirp::with('user')
            ->latest()
            ->take(50)  // Limit to 50 most recent chirps
            ->get();
 
        return view('home', ['chirps' => $chirps]);
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
    $validated = $request->validate([
        'message' => 'required|string|max:255',
    ]);

    // Use the authenticated user
    auth()->user()->chirps()->create($validated);

    return redirect('/')->with('success', 'Your chirp has been posted!');
}

public function edit(Chirp $chirp)
{
    Gate::authorize('edit', $chirp);
    return view('chirps.edit', compact('chirp'));
}

public function update(Request $request, Chirp $chirp)
{
    Gate::authorize('update', $chirp);

    $validated = $request->validate([
        'message' => 'required|string|max:255',
    ]);

    $chirp->update($validated);

    return redirect('/')->with('success', 'Chirp updated!');
}

public function destroy(Chirp $chirp)
{
    Gate::authorize('delete', $chirp);

    $chirp->delete();

    return redirect('/')->with('success', 'Chirp deleted!');
}
}

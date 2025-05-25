<?php

namespace App\Http\Controllers;

use App\Models\Wish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class WishController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $wishes = Auth::user()->wishes()->latest()->get();
        return view('wishes.index', compact('wishes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('wishes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'link' => 'nullable|url|max:255',
        ]);

        $wish = Auth::user()->wishes()->create($validated);

        return redirect()->route('wishes.index')->with('success', 'Wish created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Wish $wish): View
    {
        $this->authorize('view', $wish);
        return view('wishes.show', compact('wish'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wish $wish): View
    {
        $this->authorize('update', $wish);
        return view('wishes.edit', compact('wish'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Wish $wish): RedirectResponse
    {
        $this->authorize('update', $wish);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'link' => 'nullable|url|max:255',
        ]);

        $wish->update($validated);

        return redirect()->route('wishes.index')->with('success', 'Wish updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wish $wish): RedirectResponse
    {
        $this->authorize('delete', $wish);

        $wish->delete();

        return redirect()->route('wishes.index')->with('success', 'Wish deleted successfully!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ConnectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = Auth::user();
        $sentConnections = $user->sentConnections()->with('connectedUser')->get();
        $receivedConnections = $user->receivedConnections()->with('user')->get();
        $connections = $user->allConnections();

        return view('connections.index', compact('sentConnections', 'receivedConnections', 'connections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('connections.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'connected_user_id' => 'required|exists:users,id',
        ]);

        // Check if connection already exists
        $existingConnection = Connection::where('user_id', Auth::id())
            ->where('connected_user_id', $validated['connected_user_id'])
            ->first();

        if ($existingConnection) {
            return redirect()->route('connections.index')
                ->with('error', 'Connection request already sent.');
        }

        // Create new connection
        $connection = new Connection();
        $connection->user_id = Auth::id();
        $connection->connected_user_id = $validated['connected_user_id'];
        $connection->status = 'pending';
        $connection->save();

        return redirect()->route('connections.index')
            ->with('success', 'Connection request sent successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
        $user = User::findOrFail($id);
        $wishes = $user->wishes;

        return view('connections.show', compact('user', 'wishes'));
    }

    /**
     * Accept a connection request.
     */
    public function accept(string $id): RedirectResponse
    {
        $connection = Connection::findOrFail($id);

        // Ensure the authenticated user is the one receiving the connection request
        if ($connection->connected_user_id !== Auth::id()) {
            return redirect()->route('connections.index')
                ->with('error', 'You are not authorized to accept this connection.');
        }

        $connection->status = 'accepted';
        $connection->save();

        return redirect()->route('connections.index')
            ->with('success', 'Connection accepted successfully.');
    }

    /**
     * Reject a connection request.
     */
    public function reject(string $id): RedirectResponse
    {
        $connection = Connection::findOrFail($id);

        // Ensure the authenticated user is the one receiving the connection request
        if ($connection->connected_user_id !== Auth::id()) {
            return redirect()->route('connections.index')
                ->with('error', 'You are not authorized to reject this connection.');
        }

        $connection->status = 'rejected';
        $connection->save();

        return redirect()->route('connections.index')
            ->with('success', 'Connection rejected successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $connection = Connection::findOrFail($id);

        // Ensure the authenticated user is part of the connection
        if ($connection->user_id !== Auth::id() && $connection->connected_user_id !== Auth::id()) {
            return redirect()->route('connections.index')
                ->with('error', 'You are not authorized to delete this connection.');
        }

        $connection->delete();

        return redirect()->route('connections.index')
            ->with('success', 'Connection deleted successfully.');
    }

    /**
     * Display wishes from connected users.
     */
    public function connectedWishes(): View
    {
        $user = Auth::user();
        $connections = $user->allConnections();
        $connectedUserIds = $connections->pluck('id')->toArray();

        // Get wishes from connected users
        $wishes = \App\Models\Wish::whereIn('user_id', $connectedUserIds)
            ->with('user')
            ->latest()
            ->get();

        return view('connections.wishes', compact('wishes'));
    }
}

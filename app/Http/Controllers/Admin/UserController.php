<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
    /**
     * Liste tous les utilisateurs
     */
    public function index()
    {
        $users = User::withCount('dossiers')->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Formulaire d'édition d'un utilisateur
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Mise à jour d'un utilisateur
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'role'         => 'required|in:user,admin',
            'specialite'   => 'nullable|string|max:255',
            'organisation' => 'nullable|string|max:255',
            'telephone'    => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', "L'utilisateur {$user->name} a été mis à jour.");
    }

    /**
     * Suppression d'un utilisateur
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', "Vous ne pouvez pas supprimer votre propre compte.");
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "L'utilisateur {$userName} a été supprimé.");
    }
}

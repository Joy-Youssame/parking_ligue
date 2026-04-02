<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FileAttente;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Sécurité : Si l'utilisateur n'est pas admin, on le renvoie au dashboard normal
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', "Accès interdit : vous n'êtes pas administrateur.");
        }

        $users = User::all();
        $fileAttente = FileAttente::with('user')->orderBy('rang')->get();

        return view('admin.dashboard', compact('users', 'fileAttente'));
    }

    public function validerUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['est_valide' => 1]);

        return back()->with('success', "L'utilisateur {$user->nom} a été validé.");
    }
}

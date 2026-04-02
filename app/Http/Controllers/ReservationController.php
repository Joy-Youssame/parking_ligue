<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Place;
use App\Models\Reservation;
use App\Models\FileAttente;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function reserver()
    {
        $user = Auth::user();

        // 1. VERIFICATION : Ici je verifie si l'utilisateur a déjà une réservation en cours ou est déjà dans la file d'attente
        $dejaReserve = Reservation::where('user_id', $user->id)->whereNull('date_fin_reelle')->exists();
        $dejaEnAttente = FileAttente::where('user_id', $user->id)->exists();

        if ($dejaReserve || $dejaEnAttente) {
            return back()->with('error', 'Vous avez déjà une demande en cours ou une place occupée.');
        }

        // 2. ici je cherche une place libre aleotoirement (randomly) si aucune place n'est disponible l'utilisateur est placé en liste d'attente 
        $placeLibre = Place::where('statut', 'libre')->inRandomOrder()->first();

        if ($placeLibre) {
            // --- CAS A : UNE PLACE EST DISPONIBLE ---
            
            // On calcule la date de fin (ex: +24h avec Carbon)
            $dateFin = Carbon::now()->addHours(24); 

            // Créer la réservation
            Reservation::create([
                'user_id' => $user->id,
                'place_id' => $placeLibre->id,
                'date_debut' => Carbon::now(),
                'date_fin_prevue' => $dateFin,
            ]);

            // Mettre la place à 'occupee'
            $placeLibre->update(['statut' => 'occupee']);

            return back()->with('success', "La place n°{$placeLibre->numero} vous a été attribuée !");

        } else {
            // --- CAS B : AUCUNE PLACE LIBRE -> FILE D'ATTENTE ---
            
            $dernierRang = FileAttente::max('rang') ?? 0;
            
            FileAttente::create([
                'user_id' => $user->id,
                'rang' => $dernierRang + 1,
            ]);

            return back()->with('info', "Plus de places disponibles. Vous êtes au rang n°" . ($dernierRang + 1));
        }
    }

    public function index()
    {
        $user = auth()->user();

        // La réservation actuelle (celle qui n'a pas de date de fin réelle)
        $resaActuelle = Reservation::where('user_id', $user->id)
                                    ->whereNull('date_fin_reelle')
                                    ->first();

        // L'historique (toutes les réservations terminées)
        $historique = Reservation::where('user_id', $user->id)
                                    ->whereNotNull('date_fin_reelle')
                                    ->orderBy('date_debut', 'desc')
                                    ->get();

        // La position en file d'attente
        $attente = FileAttente::where('user_id', $user->id)->first();

        return view('dashboard', compact('resaActuelle', 'historique', 'attente'));
    }

    public function liberer($id)
    {
        $resa = Reservation::findOrFail($id);
        $place = $resa->place;

        // 1. On termine la réservation actuelle
        $resa->update(['date_fin_reelle' => now()]);

        // 2. On cherche s'il y a quelqu'un au rang 1 dans la file d'attente
        $suivant = FileAttente::orderBy('rang', 'asc')->first();

        if ($suivant) {
            // CAS A : Quelqu'un attend !
            // On lui crée une réservation immédiatement
            Reservation::create([
                'user_id' => $suivant->user_id,
                'place_id' => $place->id,
                'date_debut' => now(),
                'date_fin_prevue' => now()->addHours(24),
            ]);

            // On supprime cette personne de la file d'attente
            $suivant->delete();

            // On met à jour les rangs des autres personnes qui attendent encore (Rang - 1)
            FileAttente::where('rang', '>', 1)->decrement('rang');

            return back()->with('success', "Place libérée et réattribuée automatiquement au premier de la liste !");
        } else {
            // CAS B : Personne n'attend
            $place->update(['statut' => 'libre']);
            return back()->with('success', "Place libérée. Elle est maintenant disponible pour tous.");
        }
    }
}


<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Place; // Ne pas oublier d'importer le modèle

class PlaceSeeder extends Seeder
{
    public function run(): void
    {
        // On crée 10 places numérotées de 101 à 110
        for ($i = 101; $i <= 110; $i++) {
            Place::create([
                'numero' => $i,
                'statut' => 'libre',
            ]);
        }
    }
}
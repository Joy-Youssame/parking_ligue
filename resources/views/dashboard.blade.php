<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                
                <h3 class="text-xl font-bold mb-6">Gestion de ma place de parking</h3>

                {{-- CAS 1 : L'utilisateur a une place attribuée --}}
                @if($resaActuelle)
                    <div class="bg-green-500 text-white p-6 rounded-lg mb-6">
                        <p class="text-lg">Votre place actuelle : <strong>N°{{ $resaActuelle->place->numero }}</strong></p>
                        <p class="text-sm">Expire le : {{ \Carbon\Carbon::parse($resaActuelle->date_fin_prevue)->format('d/m/Y H:i') }}</p>
                        
                        <form action="{{ route('reservation.liberer', $resaActuelle->id) }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="bg-white text-green-600 px-4 py-2 rounded font-bold">Libérer la place maintenant</button>
                        </form>
                    </div>

                {{-- CAS 2 : L'utilisateur est en file d'attente --}}
                @elseif($attente)
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-6 rounded mb-6">
                        <p class="text-lg font-bold">Vous êtes en file d'attente</p>
                        <p>Votre rang actuel : <strong>n°{{ $attente->rang }}</strong></p>
                    </div>

                {{-- CAS 3 : L'utilisateur n'a rien (on affiche le bouton) --}}
                @else
                    <form action="{{ route('reservation.demander') }}" method="POST">
                        @csrf
                        <button type="submit" style="background-color: #2563eb; color: white; padding: 15px 30px; border-radius: 8px; font-weight: bold;">
                            DEMANDER UNE PLACE
                        </button>
                    </form>
                @endif

                {{-- SECTION HISTORIQUE (Toujours visible) --}}
                <div class="mt-10">
                    <h4 class="font-bold border-b pb-2 mb-4">Historique de mes attributions</h4>
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-2 border">Place</th>
                                <th class="p-2 border">Du</th>
                                <th class="p-2 border">Au (Rendu le)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($historique as $h)
                                <tr>
                                    <td class="p-2 border">{{ $h->place->numero }}</td>
                                    <td class="p-2 border">{{ \Carbon\Carbon::parse($h->date_debut)->format('d/m/Y') }}</td>
                                    <td class="p-2 border">{{ \Carbon\Carbon::parse($h->date_fin_reelle)->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="p-4 text-center text-gray-500">Aucun historique disponible</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
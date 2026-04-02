<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Interface Administrateur - Gestion du Parking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white p-6 shadow-sm sm:rounded-lg mb-8">
                <h3 class="text-lg font-bold mb-4 border-b pb-2">Utilisateurs à valider</h3>
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-left">
                            <th class="p-3 border">Nom / Prénom</th>
                            <th class="p-3 border">Email</th>
                            <th class="p-3 border">Statut actuel</th>
                            <th class="p-3 border">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="p-3 border">{{ $user->nom }} {{ $user->prenom }}</td>
                            <td class="p-3 border">{{ $user->email }}</td>
                            <td class="p-3 border">
                                <span class="{{ $user->est_valide ? 'text-green-600' : 'text-red-600' }} font-bold">
                                    {{ $user->est_valide ? 'Validé' : 'Non validé' }}
                                </span>
                            </td>
                            <td class="p-3 border text-center">
                                @if(!$user->est_valide)
                                    <form action="{{ route('admin.valider', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-green-600 text-white px-4 py-1 rounded hover:bg-green-700">
                                            Valider le compte
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400">Déjà validé</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <h3 class="text-lg font-bold mb-4 border-b pb-2">Liste d'attente actuelle</h3>
                @if($fileAttente->isEmpty())
                    <p class="text-gray-500">Personne n'est en attente de place pour le moment.</p>
                @else
                    <table class="w-full border-collapse">
                        <tr class="bg-gray-50 text-left">
                            <th class="p-3 border">Rang</th>
                            <th class="p-3 border">Utilisateur</th>
                            <th class="p-3 border">Date de demande</th>
                        </tr>
                        @foreach($fileAttente as $attente)
                        <tr>
                            <td class="p-3 border"><strong>{{ $attente->rang }}</strong></td>
                            <td class="p-3 border">{{ $attente->user->nom }} {{ $attente->user->prenom }}</td>
                            <td class="p-3 border">{{ $attente->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </table>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
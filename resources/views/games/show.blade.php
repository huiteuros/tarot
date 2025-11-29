<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails de la Partie') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-start mb-6">
                        <h3 class="text-2xl font-bold">Détails de la Partie</h3>
                        <div class="flex gap-2">
                            <a href="{{ route('games.create', ['players' => $playerIds]) }}" 
                                class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                🔄 Rejouer avec les mêmes joueurs
                            </a>
                            <a href="{{ route('games.index') }}" class="text-gray-600 hover:text-gray-800">
                                ← Retour
                            </a>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <span>{{ session('success') }}</span>
                                @if (session('player_ids'))
                                    <a href="{{ route('games.create', ['players' => session('player_ids')]) }}" 
                                        class="ml-4 inline-flex items-center px-3 py-1 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                        ➕ Nouvelle partie
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="font-semibold text-lg mb-3">Informations Générales</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm text-gray-600">Date et Heure</dt>
                                    <dd class="font-medium">{{ $game->played_at->format('d/m/Y à H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-600">Type de Contrat</dt>
                                    <dd class="font-medium">
                                        @if($game->contract_type === 'petite') 🃏 Petite
                                        @elseif($game->contract_type === 'garde') 🛡️ Garde
                                        @elseif($game->contract_type === 'garde_sans') ⚔️ Garde Sans
                                        @else 👑 Garde Contre
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-600">Résultat</dt>
                                    <dd>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $game->contract_success ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $game->contract_success ? '✅ Contrat Réussi' : '❌ Contrat Chuté' }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h4 class="font-semibold text-lg mb-3">Scores</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm text-gray-600">Points</dt>
                                    <dd class="font-medium">{{ $game->points }} points</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-600">Bouts</dt>
                                    <dd class="font-medium">{{ $game->oudlers }} bout(s)</dd>
                                </div>
                                @if($game->petit_au_bout)
                                    <div>
                                        <dt class="text-sm text-gray-600">Petit au bout</dt>
                                        <dd class="font-medium">🎯 {{ ucfirst($game->petit_au_bout_team) }}</dd>
                                    </div>
                                @endif
                                @if($game->poignee !== 'aucune')
                                    <div>
                                        <dt class="text-sm text-gray-600">Poignée</dt>
                                        <dd class="font-medium">
                                            🖐️ {{ ucfirst($game->poignee) }} 
                                            ({{ ucfirst($game->poignee_team) }})
                                        </dd>
                                    </div>
                                @endif
                                @if($game->chelem !== 'aucun')
                                    <div>
                                        <dt class="text-sm text-gray-600">Chelem</dt>
                                        <dd class="font-medium">
                                            👑 
                                            @if($game->chelem === 'annonce_reussi') Annoncé et réussi
                                            @elseif($game->chelem === 'annonce_chute') Annoncé mais chuté
                                            @else Non annoncé
                                            @endif
                                        </dd>
                                    </div>
                                @endif
                                @if($game->miseres->count() > 0)
                                    <div>
                                        <dt class="text-sm text-gray-600">Misères</dt>
                                        <dd class="font-medium">
                                            @foreach($game->miseres as $misere)
                                                <div class="text-sm">
                                                    💀 {{ $misere->user->name }} - 
                                                    @if($misere->type === 'tetes') 👤 Têtes
                                                    @else 🃏 Atouts
                                                    @endif
                                                </div>
                                            @endforeach
                                        </dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <hr class="my-6">

                    <div>
                        <h4 class="font-semibold text-lg mb-4">Joueurs et Évolution ELO</h4>
                        
                        @php
                            $preneurs = $game->gamePlayers->where('role', 'preneur');
                            $attaquants = $game->gamePlayers->where('role', 'attaquant');
                            $defenseurs = $game->gamePlayers->where('role', 'defenseur');
                        @endphp

                        @if($preneurs->count() > 0 || $attaquants->count() > 0)
                            <div class="mb-6">
                                <h5 class="font-medium mb-2 {{ $game->contract_success ? 'text-green-700' : 'text-red-700' }}">
                                    {{ $game->contract_success ? '✅' : '❌' }} Attaquants
                                </h5>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <table class="min-w-full">
                                        <thead>
                                            <tr class="text-xs text-gray-600">
                                                <th class="text-left pb-2">Joueur</th>
                                                <th class="text-right pb-2">Rôle</th>
                                                <th class="text-right pb-2">Score</th>
                                                <th class="text-right pb-2">ELO Avant</th>
                                                <th class="text-right pb-2">Changement</th>
                                                <th class="text-right pb-2">ELO Après</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($preneurs->merge($attaquants) as $player)
                                                <tr class="border-t border-gray-200">
                                                    <td class="py-2 font-medium">{{ $player->user->name }}</td>
                                                    <td class="text-right">
                                                        <span class="text-xs px-2 py-1 bg-indigo-100 text-indigo-800 rounded">
                                                            {{ $player->role === 'preneur' ? 'Preneur' : 'Attaquant' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-right {{ $player->score > 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                                                        {{ $player->score > 0 ? '+' : '' }}{{ $player->score }}
                                                    </td>
                                                    <td class="text-right">{{ $player->elo_before }}</td>
                                                    <td class="text-right {{ $player->elo_change > 0 ? 'text-green-600' : 'text-red-600' }} font-bold">
                                                        {{ $player->elo_change > 0 ? '+' : '' }}{{ $player->elo_change }}
                                                    </td>
                                                    <td class="text-right font-bold">{{ $player->elo_after }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        @if($defenseurs->count() > 0)
                            <div>
                                <h5 class="font-medium mb-2 {{ !$game->contract_success ? 'text-green-700' : 'text-red-700' }}">
                                    {{ !$game->contract_success ? '✅' : '❌' }} Défenseurs
                                </h5>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <table class="min-w-full">
                                        <thead>
                                            <tr class="text-xs text-gray-600">
                                                <th class="text-left pb-2">Joueur</th>
                                                <th class="text-right pb-2">Rôle</th>
                                                <th class="text-right pb-2">Score</th>
                                                <th class="text-right pb-2">ELO Avant</th>
                                                <th class="text-right pb-2">Changement</th>
                                                <th class="text-right pb-2">ELO Après</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($defenseurs as $player)
                                                <tr class="border-t border-gray-200">
                                                    <td class="py-2 font-medium">{{ $player->user->name }}</td>
                                                    <td class="text-right">
                                                        <span class="text-xs px-2 py-1 bg-gray-100 text-gray-800 rounded">
                                                            Défenseur
                                                        </span>
                                                    </td>
                                                    <td class="text-right {{ $player->score > 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                                                        {{ $player->score > 0 ? '+' : '' }}{{ $player->score }}
                                                    </td>
                                                    <td class="text-right">{{ $player->elo_before }}</td>
                                                    <td class="text-right {{ $player->elo_change > 0 ? 'text-green-600' : 'text-red-600' }} font-bold">
                                                        {{ $player->elo_change > 0 ? '+' : '' }}{{ $player->elo_change }}
                                                    </td>
                                                    <td class="text-right font-bold">{{ $player->elo_after }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

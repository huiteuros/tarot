<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Historique des Parties') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold">📋 Historique des Parties</h3>
                        <a href="{{ route('games.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            ➕ Nouvelle Partie
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 rounded-lg p-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($games->isEmpty())
                        <p class="text-gray-600">Aucune partie enregistrée.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($games as $game)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="text-lg font-bold">
                                                    @if($game->contract_type === 'petite') 🃏 Petite
                                                    @elseif($game->contract_type === 'garde') 🛡️ Garde
                                                    @elseif($game->contract_type === 'garde_sans') ⚔️ Garde Sans
                                                    @else 👑 Garde Contre
                                                    @endif
                                                </span>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $game->contract_success ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $game->contract_success ? 'Réussi' : 'Chuté' }}
                                                </span>
                                            </div>
                                            
                                            <div class="text-sm text-gray-600 mb-3">
                                                📅 {{ $game->played_at->format('d/m/Y H:i') }} | 
                                                📊 {{ $game->points }} points | 
                                                🎯 {{ $game->oudlers }} bout(s)
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                @php
                                                    $preneurs = $game->gamePlayers->where('role', 'preneur');
                                                    $attaquants = $game->gamePlayers->where('role', 'attaquant');
                                                    $defenseurs = $game->gamePlayers->where('role', 'defenseur');
                                                @endphp

                                                <div>
                                                    <div class="font-semibold text-sm mb-1">
                                                        {{ $game->contract_success ? '✅ Attaquants' : '❌ Attaquants' }}
                                                    </div>
                                                    <ul class="text-sm space-y-1">
                                                        @foreach($preneurs->merge($attaquants) as $player)
                                                            <li class="flex justify-between">
                                                                <span>{{ $player->user->name }}</span>
                                                                <span class="text-gray-600">
                                                                    {{ $player->score > 0 ? '+' : '' }}{{ $player->score }} pts |
                                                                    ELO: {{ $player->elo_before }} 
                                                                    <span class="{{ $player->elo_change > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                                        {{ $player->elo_change > 0 ? '+' : '' }}{{ $player->elo_change }}
                                                                    </span>
                                                                </span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>

                                                <div>
                                                    <div class="font-semibold text-sm mb-1">
                                                        {{ !$game->contract_success ? '✅ Défenseurs' : '❌ Défenseurs' }}
                                                    </div>
                                                    <ul class="text-sm space-y-1">
                                                        @foreach($defenseurs as $player)
                                                            <li class="flex justify-between">
                                                                <span>{{ $player->user->name }}</span>
                                                                <span class="text-gray-600">
                                                                    {{ $player->score > 0 ? '+' : '' }}{{ $player->score }} pts |
                                                                    ELO: {{ $player->elo_before }} 
                                                                    <span class="{{ $player->elo_change > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                                        {{ $player->elo_change > 0 ? '+' : '' }}{{ $player->elo_change }}
                                                                    </span>
                                                                </span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex gap-3">
                                            @if($latestGame && $game->id === $latestGame->id)
                                                <a href="{{ route('games.edit', $game) }}" class="text-indigo-600 hover:text-indigo-800">
                                                    ✏️ Modifier
                                                </a>
                                            @endif
                                            <a href="{{ route('games.show', $game) }}" class="text-indigo-600 hover:text-indigo-800">
                                                Détails →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $games->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

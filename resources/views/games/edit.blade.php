<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier la Partie') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-6">✏️ Modifier une Partie</h3>

                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 rounded-lg p-4">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('games.update', $game) }}" class="space-y-6" x-data="gameForm()">
                        @csrf
                        @method('PUT')

                        <!-- Étape 1 : Sélection des joueurs -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h4 class="font-semibold text-lg mb-3">
                                1️⃣ Sélectionnez les joueurs
                                <span x-show="selectedPlayers.length > 0" class="text-green-600 text-sm">
                                    (<span x-text="selectedPlayers.length"></span>/<span x-text="nombreJoueurs"></span> ✓)
                                </span>
                            </h4>
                            
                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-2">
                                    <label class="block text-sm font-medium text-gray-700">Nombre de joueurs</label>
                                    <button type="button" 
                                        @click="selectedPlayers = []; preneurId = ''; attaquantId = '';"
                                        x-show="selectedPlayers.length > 0"
                                        class="text-xs text-red-600 hover:text-red-800">
                                        🔄 Réinitialiser
                                    </button>
                                </div>
                                <div class="flex gap-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="nombre_joueurs" value="4" x-model="nombreJoueurs"
                                            class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2">4 joueurs</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="nombre_joueurs" value="5" x-model="nombreJoueurs"
                                            class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2">5 joueurs</span>
                                    </label>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Choisissez <span x-text="nombreJoueurs"></span> joueurs
                                </label>
                                @foreach($users as $user)
                                    <label class="flex items-center p-2 hover:bg-blue-100 rounded" 
                                        x-bind:class="{ 'bg-blue-50': selectedPlayers.includes('{{ $user->id }}') }">
                                        <input type="checkbox" 
                                            name="player_ids[]" 
                                            value="{{ $user->id }}"
                                            x-bind:checked="selectedPlayers.includes('{{ $user->id }}')"
                                            @change="togglePlayer('{{ $user->id }}')"
                                            x-bind:disabled="selectedPlayers.length >= parseInt(nombreJoueurs) && !selectedPlayers.includes('{{ $user->id }}')"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2 flex-1">{{ $user->name }}</span>
                                        <span class="text-sm text-gray-500">ELO: {{ $user->elo }}</span>
                                        <span x-show="selectedPlayers.includes('{{ $user->id }}')" class="ml-2 text-green-600">✓</span>
                                    </label>
                                @endforeach
                                <p class="text-sm text-gray-600 mt-2">
                                    <span x-text="selectedPlayers.length"></span> / <span x-text="nombreJoueurs"></span> joueurs sélectionnés
                                </p>
                            </div>
                        </div>

                        <!-- Étape 2 : Détails de la partie -->
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4" x-show="selectedPlayers.length == parseInt(nombreJoueurs)">
                            <h4 class="font-semibold text-lg mb-3">2️⃣ Détails de la partie</h4>

                            <div class="space-y-4">
                                <div>
                                    <label for="played_at" class="block text-sm font-medium text-gray-700">Date et Heure</label>
                                    <input type="datetime-local" name="played_at" id="played_at" value="{{ old('played_at', $game->played_at->format('Y-m-d\TH:i')) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label for="preneur_id" class="block text-sm font-medium text-gray-700">Qui a pris ?</label>
                                    <select name="preneur_id" id="preneur_id" x-model="preneurId" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Sélectionnez le preneur...</option>
                                        <template x-for="playerId in selectedPlayers" :key="playerId">
                                            <option :value="playerId" x-text="getPlayerName(playerId)" :selected="playerId == preneurId"></option>
                                        </template>
                                    </select>
                                </div>

                                <div x-show="nombreJoueurs == '5' && preneurId">
                                    <label for="attaquant_id" class="block text-sm font-medium text-gray-700">Appel (Roi/Cavalier appelé)</label>
                                    <select name="attaquant_id" id="attaquant_id" x-model="attaquantId"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Le preneur joue seul</option>
                                        <template x-for="playerId in selectedPlayers" :key="playerId">
                                            <option x-show="playerId != preneurId" :value="playerId" x-text="getPlayerName(playerId)" :selected="playerId == attaquantId"></option>
                                        </template>
                                    </select>
                                </div>

                                <div>
                                    <label for="contract_type" class="block text-sm font-medium text-gray-700">Type de Contrat</label>
                                    <select name="contract_type" id="contract_type" required x-model="contractType"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="petite">Petite</option>
                                        <option value="garde">Garde</option>
                                        <option value="garde_sans">Garde Sans</option>
                                        <option value="garde_contre">Garde Contre</option>
                                    </select>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="points" class="block text-sm font-medium text-gray-700">Points du Preneur</label>
                                        <input type="number" name="points" id="points" min="0" max="91" value="{{ old('points', $game->points) }}" required
                                            x-model="points"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    <div>
                                        <label for="oudlers" class="block text-sm font-medium text-gray-700">Nombre de Bouts</label>
                                        <select name="oudlers" id="oudlers" required
                                            x-model="oudlers"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="0">0 bout</option>
                                            <option value="1">1 bout</option>
                                            <option value="2">2 bouts</option>
                                            <option value="3">3 bouts</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Affichage automatique du résultat -->
                                <div x-show="points !== '' && oudlers !== ''" class="p-3 rounded-lg" :class="contractSuccess ? 'bg-green-100 border border-green-300' : 'bg-red-100 border border-red-300'">
                                    <p class="font-medium" :class="contractSuccess ? 'text-green-800' : 'text-red-800'">
                                        <span x-show="contractSuccess">✅ Contrat réussi !</span>
                                        <span x-show="!contractSuccess">❌ Contrat chuté !</span>
                                    </p>
                                    <p class="text-sm mt-1" :class="contractSuccess ? 'text-green-700' : 'text-red-700'">
                                        <span x-text="points"></span> points avec <span x-text="oudlers"></span> bout<span x-show="oudlers > 1">s</span>
                                        (seuil : <span x-text="requiredPoints"></span> points)
                                    </p>
                                </div>
                                
                                <!-- Champ caché pour envoyer le résultat calculé -->
                                <input type="hidden" name="contract_success" :value="contractSuccess ? '1' : '0'">
                            </div>
                        </div>

                        <!-- Étape 3 : Bonus -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4" x-show="selectedPlayers.length == parseInt(nombreJoueurs)">
                            <h4 class="font-semibold text-lg mb-3">3️⃣ Bonus et Annonces (Optionnel)</h4>

                            <div class="space-y-4">
                                <!-- Petit au bout -->
                                <div>
                                    <label class="flex items-center mb-2">
                                        <input type="checkbox" name="petit_au_bout" value="1" x-model="petitAuBout"
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2 font-medium">🎯 Petit au bout (+10 points)</span>
                                    </label>
                                    <div x-show="petitAuBout" class="ml-6 flex gap-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="petit_au_bout_team" value="attaque" x-model="petitAuBoutTeam"
                                                class="rounded-full border-gray-300 text-indigo-600">
                                            <span class="ml-2">Attaque</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="petit_au_bout_team" value="defense" x-model="petitAuBoutTeam"
                                                class="rounded-full border-gray-300 text-indigo-600">
                                            <span class="ml-2">Défense</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Poignée -->
                                <div>
                                    <label for="poignee" class="block text-sm font-medium text-gray-700">🖐️ Poignée</label>
                                    <select name="poignee" id="poignee" x-model="poignee"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="aucune">Aucune</option>
                                        <option value="simple">Simple - 10 atouts (+20 points)</option>
                                        <option value="double">Double - 13 atouts (+30 points)</option>
                                        <option value="triple">Triple - 15 atouts (+40 points)</option>
                                    </select>
                                    <div x-show="poignee != 'aucune'" class="mt-2 flex gap-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="poignee_team" value="attaque" x-model="poigneeTeam"
                                                class="rounded-full border-gray-300 text-indigo-600">
                                            <span class="ml-2">Attaque</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="poignee_team" value="defense" x-model="poigneeTeam"
                                                class="rounded-full border-gray-300 text-indigo-600">
                                            <span class="ml-2">Défense</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Chelem -->
                                <div>
                                    <label for="chelem" class="block text-sm font-medium text-gray-700">👑 Chelem</label>
                                    <select name="chelem" id="chelem" x-model="chelem"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="aucun">Aucun</option>
                                        <option value="annonce_reussi">Chelem annoncé et réussi (+400 points)</option>
                                        <option value="annonce_chute">Chelem annoncé mais chuté (-200 points)</option>
                                        <option value="non_annonce">Chelem non annoncé réussi (+200 points)</option>
                                    </select>
                                </div>

                                <!-- Misères individuelles -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">💀 Misères (+10 points par joueur)</label>
                                    <div class="space-y-2 bg-gray-50 p-3 rounded">
                                        <template x-for="playerId in selectedPlayers" :key="playerId">
                                            <div class="flex items-center gap-4">
                                                <span class="w-32 text-sm font-medium" x-text="getPlayerName(playerId)"></span>
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" 
                                                        :name="'miseres[' + playerId + '][tetes]'" 
                                                        value="1"
                                                        :checked="hasMisere(playerId, 'tetes')"
                                                        class="rounded border-gray-300 text-indigo-600">
                                                    <span class="ml-2 text-sm">👤 Têtes</span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="checkbox" 
                                                        :name="'miseres[' + playerId + '][atouts]'" 
                                                        value="1"
                                                        :checked="hasMisere(playerId, 'atouts')"
                                                        class="rounded border-gray-300 text-indigo-600">
                                                    <span class="ml-2 text-sm">🃏 Atouts</span>
                                                </label>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4" x-show="selectedPlayers.length == parseInt(nombreJoueurs)">
                            <a href="{{ route('games.show', $game) }}" class="text-gray-600 hover:text-gray-800">Annuler</a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                💾 Mettre à jour la Partie
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function gameForm() {
            return {
                nombreJoueurs: '{{ $game->nombre_joueurs }}',
                selectedPlayers: @json($game->gamePlayers->pluck('user_id')->map(fn($id) => (string)$id)->toArray()),
                preneurId: '{{ $game->gamePlayers->where("role", "preneur")->first()->user_id ?? "" }}',
                attaquantId: '{{ $game->gamePlayers->where("role", "attaquant")->first()->user_id ?? "" }}',
                points: '{{ old("points", $game->points) }}',
                oudlers: '{{ old("oudlers", $game->oudlers) }}',
                contractType: '{{ old("contract_type", $game->contract_type) }}',
                petitAuBout: {{ $game->petit_au_bout ? 'true' : 'false' }},
                petitAuBoutTeam: '{{ old("petit_au_bout_team", $game->petit_au_bout_team ?? "attaque") }}',
                poignee: '{{ old("poignee", $game->poignee ?? "aucune") }}',
                poigneeTeam: '{{ old("poignee_team", $game->poignee_team ?? "attaque") }}',
                chelem: '{{ old("chelem", $game->chelem ?? "aucun") }}',
                miseres: @json($game->miseres->groupBy('user_id')->mapWithKeys(function($miseres, $userId) {
                    return [$userId => $miseres->pluck('type')->toArray()];
                })->toArray()),
                users: @json($users),
                
                // Calcul automatique du seuil requis
                get requiredPoints() {
                    const seuils = { 0: 56, 1: 51, 2: 41, 3: 36 };
                    return seuils[this.oudlers] || 56;
                },
                
                // Calcul automatique du succès du contrat
                get contractSuccess() {
                    if (this.points === '') return false;
                    return parseFloat(this.points) >= this.requiredPoints;
                },
                
                init() {
                    // Les données sont déjà initialisées depuis PHP
                    this.$nextTick(() => {
                        this.selectedPlayers.forEach(playerId => {
                            const checkbox = document.querySelector(`input[type="checkbox"][value="${playerId}"]`);
                            if (checkbox) {
                                checkbox.checked = true;
                            }
                        });
                    });
                },
                
                togglePlayer(playerId) {
                    playerId = String(playerId);
                    const index = this.selectedPlayers.indexOf(playerId);
                    
                    if (index > -1) {
                        // Joueur déjà sélectionné, on le retire
                        this.selectedPlayers.splice(index, 1);
                    } else {
                        // Joueur pas encore sélectionné, on l'ajoute
                        if (this.selectedPlayers.length < parseInt(this.nombreJoueurs)) {
                            this.selectedPlayers.push(playerId);
                        }
                    }
                },
                
                getPlayerName(playerId) {
                    const user = this.users.find(u => u.id == playerId);
                    return user ? `${user.name} (ELO: ${user.elo})` : '';
                },
                
                hasMisere(playerId, type) {
                    return this.miseres[playerId] && this.miseres[playerId].includes(type);
                }
            }
        }
    </script>
</x-app-layout>

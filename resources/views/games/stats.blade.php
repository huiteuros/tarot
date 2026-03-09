<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $isOwnProfile ? __('Mes Statistiques') : __('Statistiques de ' . $user->name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-6">📊 Statistiques de {{ $user->name }}</h3>

                    @if($totalGames === 0)
                        <div class="text-center py-12">
                            <p class="text-gray-600 text-lg mb-4">{{ $isOwnProfile ? 'Vous n\'avez pas encore joué de partie.' : $user->name . ' n\'a pas encore joué de partie.' }}</p>
                            @if($isOwnProfile)
                                <a href="{{ route('games.create') }}" 
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                    ➕ Créer votre première partie
                                </a>
                            @endif
                        </div>
                    @else
                        <!-- Statistiques générales -->
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="text-sm text-gray-600 mb-1">Parties Jouées</div>
                                <div class="text-3xl font-bold text-blue-600">{{ $totalGames }}</div>
                            </div>

                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="text-sm text-gray-600 mb-1">Win Rate Global</div>
                                <div class="text-3xl font-bold text-green-600">{{ $winRate }}%</div>
                            </div>

                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                <div class="text-sm text-gray-600 mb-1">ELO Actuel</div>
                                <div class="text-3xl font-bold text-purple-600">{{ $user->elo }}</div>
                            </div>

                            <div class="bg-pink-50 border border-pink-200 rounded-lg p-4">
                                <div class="text-sm text-gray-600 mb-1">Peak ELO</div>
                                <div class="text-3xl font-bold text-pink-600">{{ $peakElo }}</div>
                            </div>

                            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                                <div class="text-sm text-gray-600 mb-1">Fois Preneur</div>
                                <div class="text-3xl font-bold text-orange-600">{{ $timesTaken }}</div>
                            </div>
                        </div>

                        <!-- Ligne secondaire de stats -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                            <div class="bg-teal-50 border border-teal-200 rounded-lg p-4">
                                <div class="text-sm text-gray-600 mb-1">Fois Appelé</div>
                                <div class="text-3xl font-bold text-teal-600">{{ $timesCalled }}</div>
                            </div>

                            <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                                <div class="text-sm text-gray-600 mb-1">Meilleure Victoire</div>
                                <div class="text-3xl font-bold text-emerald-600">
                                    {{ $bestWin !== null ? '+' . $bestWin : '-' }}
                                </div>
                            </div>

                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <div class="text-sm text-gray-600 mb-1">Plus Grosse Défaite</div>
                                <div class="text-3xl font-bold text-red-600">
                                    {{ $worstDefeat !== null ? $worstDefeat : '-' }}
                                </div>
                            </div>
                        </div>

                        <!-- Graphique d'évolution de l'ELO -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-8">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                                <h4 class="text-lg font-semibold">📈 Évolution de votre ELO</h4>
                                <div class="flex items-center gap-2 mt-2 sm:mt-0">
                                    <span class="text-sm text-gray-500">Afficher :</span>
                                    <div class="flex gap-1">
                                        <button onclick="filterChart(10)" class="chart-filter-btn px-3 py-1 text-xs rounded-full border border-gray-300 hover:bg-indigo-100 hover:border-indigo-400 transition">10</button>
                                        <button onclick="filterChart(20)" class="chart-filter-btn px-3 py-1 text-xs rounded-full border border-gray-300 hover:bg-indigo-100 hover:border-indigo-400 transition">20</button>
                                        <button onclick="filterChart(50)" class="chart-filter-btn px-3 py-1 text-xs rounded-full border border-gray-300 hover:bg-indigo-100 hover:border-indigo-400 transition">50</button>
                                        <button onclick="filterChart(0)" class="chart-filter-btn active px-3 py-1 text-xs rounded-full border border-indigo-500 bg-indigo-500 text-white transition">Tout</button>
                                    </div>
                                </div>
                            </div>
                            <canvas id="eloChart" class="w-full" style="max-height: 400px;"></canvas>
                        </div>

                        <!-- Statistiques en tant que preneur -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-6">
                                <h4 class="text-lg font-semibold mb-4">🎯 En tant que Preneur</h4>
                                <dl class="space-y-3">
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Nombre de prises :</dt>
                                        <dd class="font-bold">{{ $timesTaken }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Win rate :</dt>
                                        <dd class="font-bold {{ $preneurWinRate >= 50 ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $preneurWinRate }}%
                                        </dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Points moyens :</dt>
                                        <dd class="font-bold">{{ $avgPoints }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-gray-600">Bouts moyens :</dt>
                                        <dd class="font-bold">{{ $avgOudlers }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <!-- Meilleur partenaire -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                                <h4 class="text-lg font-semibold mb-4">🤝 Votre Meilleur Partenaire</h4>
                                @if($bestPartner)
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-yellow-700 mb-2">{{ $bestPartner['name'] }}</div>
                                        <div class="space-y-2">
                                            <div class="text-sm text-gray-600">
                                                <span class="font-semibold">{{ $bestPartner['games'] }}</span> parties ensemble
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                <span class="font-semibold">{{ $bestPartner['wins'] }}</span> victoires
                                            </div>
                                            <div class="text-3xl font-bold {{ $bestPartner['winRate'] >= 50 ? 'text-green-600' : 'text-red-600' }} mt-3">
                                                {{ $bestPartner['winRate'] }}%
                                            </div>
                                            <div class="text-xs text-gray-500">Win rate ensemble</div>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-gray-600 text-center">Vous n'avez pas encore joué avec un partenaire.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Statistiques par type de contrat -->
                        @if($timesTaken > 0)
                            <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
                                <h4 class="text-lg font-semibold mb-4">📋 Stats par type de contrat (en tant que preneur)</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contrat</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Parties</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Victoires</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Win Rate</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Pts Moy.</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Bouts Moy.</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($statsByContract as $contractKey => $contract)
                                                <tr class="{{ $contract['count'] === 0 ? 'opacity-40' : '' }}">
                                                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $contract['label'] }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $contract['count'] }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $contract['wins'] }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                                        @if($contract['count'] > 0)
                                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $contract['winRate'] >= 50 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                                {{ $contract['winRate'] }}%
                                                            </span>
                                                        @else
                                                            <span class="text-gray-400">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $contract['count'] > 0 ? $contract['avgPoints'] : '-' }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $contract['count'] > 0 ? $contract['avgOudlers'] : '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <!-- Tous les partenaires -->
                        @if(count($partnerStats) > 1)
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h4 class="text-lg font-semibold mb-4">👥 Tous vos Partenaires en Attaque</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Partenaire</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Parties</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Victoires</th>
                                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Win Rate</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($partnerStats as $partner)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $partner['name'] }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $partner['games'] }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $partner['wins'] }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $partner['winRate'] >= 50 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                            {{ $partner['winRate'] }}%
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($totalGames > 0)
        <style>
            .chart-filter-btn.active {
                background-color: rgb(99 102 241);
                border-color: rgb(99 102 241);
                color: white;
            }
        </style>
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            let eloChartInstance = null;
            const fullEloHistory = @json($eloHistory);

            document.addEventListener('DOMContentLoaded', function() {
                renderChart(0);
            });

            function filterChart(lastN) {
                // Update active button
                document.querySelectorAll('.chart-filter-btn').forEach(btn => {
                    btn.classList.remove('active', 'bg-indigo-500', 'text-white', 'border-indigo-500');
                    btn.classList.add('border-gray-300');
                });
                event.target.classList.add('active', 'bg-indigo-500', 'text-white', 'border-indigo-500');
                event.target.classList.remove('border-gray-300');

                renderChart(lastN);
            }

            function renderChart(lastN) {
                const ctx = document.getElementById('eloChart').getContext('2d');

                let eloHistory = fullEloHistory;
                let startLabel = 'Départ';
                let startElo = 1500;

                if (lastN > 0 && fullEloHistory.length > lastN) {
                    eloHistory = fullEloHistory.slice(-lastN);
                    // Use the elo_before of the first visible game as starting point
                    startElo = eloHistory[0].elo_before;
                    startLabel = 'Début (ELO ' + startElo + ')';
                }

                const labels = [startLabel];
                const data = [startElo];

                eloHistory.forEach((entry, index) => {
                    labels.push(`Partie ${lastN > 0 && fullEloHistory.length > lastN ? fullEloHistory.length - eloHistory.length + index + 1 : index + 1}`);
                    data.push(entry.elo_after);
                });

                if (eloChartInstance) {
                    eloChartInstance.destroy();
                }

                eloChartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'ELO',
                            data: data,
                            borderColor: 'rgb(99, 102, 241)',
                            backgroundColor: 'rgba(99, 102, 241, 0.1)',
                            tension: 0.1,
                            fill: true,
                            pointRadius: 3,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    title: function(context) {
                                        if (context[0].dataIndex === 0) {
                                            return 'ELO de départ';
                                        }
                                        const index = context[0].dataIndex - 1;
                                        if (eloHistory[index]) {
                                            const date = new Date(eloHistory[index].date);
                                            return date.toLocaleDateString('fr-FR', { 
                                                day: '2-digit', 
                                                month: '2-digit', 
                                                year: 'numeric',
                                                hour: '2-digit',
                                                minute: '2-digit'
                                            });
                                        }
                                        return context[0].label;
                                    },
                                    label: function(context) {
                                        return 'ELO: ' + context.parsed.y;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: false,
                                ticks: {
                                    stepSize: 50
                                }
                            },
                            x: {
                                ticks: {
                                    maxRotation: 45,
                                    minRotation: 45,
                                    autoSkip: true,
                                    maxTicksLimit: 20
                                }
                            }
                        }
                    }
                });
            }
        </script>
    @endif
</x-app-layout>

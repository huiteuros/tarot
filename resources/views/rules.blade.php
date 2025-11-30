<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Règles du Tarot') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Introduction -->
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold mb-4">🃏 Bienvenue au Tarot !</h3>
                        <p class="text-gray-700 leading-relaxed">
                            Le tarot est un jeu de cartes qui se joue à 3, 4 ou 5 joueurs avec un jeu de 78 cartes. 
                            Voici les règles essentielles pour débuter. Merci L'IA de m'avoir aider à rédiger les règles, par contre va réviser tu fais pas mal d'erreurs nullos.
                        </p>
                    </div>

                    <!-- Le jeu de cartes -->
                    <div class="mb-8 border-l-4 border-indigo-500 pl-4">
                        <h4 class="text-xl font-bold mb-3">🎴 Le Jeu de Cartes</h4>
                        <p class="mb-3">Un jeu de tarot contient <strong>78 cartes</strong> :</p>
                        <ul class="list-disc list-inside space-y-2 text-gray-700">
                            <li><strong>4 couleurs classiques</strong> (♠️ Pique, ♥️ Cœur, ♦️ Carreau, ♣️ Trèfle) de 14 cartes chacune : 
                                Roi, Dame, Cavalier, Valet, 10, 9, 8, 7, 6, 5, 4, 3, 2, As</li>
                            <li><strong>21 atouts</strong> numérotés de 1 à 21 (qui battent toutes les couleurs)</li>
                            <li><strong>1 Excuse</strong> (carte spéciale)</li>
                        </ul>
                    </div>

                    <!-- Les bouts -->
                    <div class="mb-8 border-l-4 border-yellow-500 pl-4">
                        <h4 class="text-xl font-bold mb-3">⭐ Les 3 Bouts</h4>
                        <p class="mb-3">Les bouts sont les cartes les plus importantes du jeu :</p>
                        <ul class="list-disc list-inside space-y-2 text-gray-700">
                            <li><strong>Le Petit (atout 1)</strong> - Vaut 4,5 points</li>
                            <li><strong>Le 21</strong> - Vaut 4,5 points</li>
                            <li><strong>L'Excuse</strong> - Vaut 4,5 points</li>
                        </ul>
                        <p class="mt-3 text-sm bg-yellow-50 p-3 rounded">
                            💡 <strong>Important :</strong> Le nombre de bouts détermine le seuil de points à atteindre pour gagner !
                        </p>
                    </div>

                    <!-- Déroulement d'une partie -->
                    <div class="mb-8 border-l-4 border-green-500 pl-4">
                        <h4 class="text-xl font-bold mb-3">🎯 Déroulement d'une Partie</h4>
                        
                        <h5 class="font-semibold mb-2">1. La Distribution</h5>
                        <p class="mb-4 text-gray-700">
                            Le donneur distribue les cartes 3 par 3. Au milieu de la distribution, il constitue le <strong>chien</strong> 
                            (6 cartes mises de côté face cachée).
                        </p>

                        <h5 class="font-semibold mb-2">2. Les Enchères</h5>
                        <p class="mb-2 text-gray-700">Chaque joueur, à tour de rôle, peut :</p>
                        <ul class="list-disc list-inside space-y-1 text-gray-700 mb-4">
                            <li><strong>Passer</strong> - Je ne prends pas</li>
                            <li><strong>Petite</strong> - Je prends (×1)</li>
                            <li><strong>Garde</strong> - Je prends avec plus de risque (×2)</li>
                            <li><strong>Garde sans le chien</strong> - Je ne regarde pas le chien mais je le garde (×4)</li>
                            <li><strong>Garde contre le chien</strong> - Je ne regarde pas le chien et je le donne aux adversaires (×6)</li>
                        </ul>

                        <h5 class="font-semibold mb-2">3. Le Chien</h5>
                        <p class="mb-4 text-gray-700">
                            Le preneur (celui qui a pris) retourne le chien, le prend dans son jeu, puis écarte 6 cartes face cachée 
                            (pas de Rois, ni de bouts, ni d'atouts sauf s'il n'a pas le choix).
                            Attention, à 5, le chien ne contient que 3 cartes !
                        </p>

                        <h5 class="font-semibold mb-2">4. Un jeu de pli</h5>
                        <ul class="list-disc list-inside space-y-2 text-gray-700 mb-4">
                            <li>Le joueur à droite du donneur commence</li>
                            <li>On doit <strong>fournir la couleur demandée</strong> si on l'a</li>
                            <li>Si on n'a pas la couleur, on doit <strong>OBLIGATOIREMENT couper avec un atout</strong></li>
                            <li>Si on coupe et qu'un atout a déjà été joué, on doit <strong>monter</strong> (jouer un atout plus fort) si possible</li>
                            <li>Si on ne peut pas monter, on doit quand même <strong>sous-couper</strong> (jouer un atout même plus faible)</li>
                            <li>Ce n'est que si on n'a <strong>ni la couleur ni d'atout</strong> qu'on peut se défausser (on dit "pisser")</li>
                            <li>L'Excuse peut être jouée à tout moment et est toujours récupéré par la personne qui l'a joué mais ne remporte jamais le pli (sauf si c'est la dernière carte de la partie, dans ce cas là elle est perdue). C'est une carte "bonus" qu'on peut jouer pour éviter de perdre un atout ou une carte qui vaut des points</li>
                            <li>Le joueur qui a la carte la plus haute remporte le pli, il commence le pli suivant</li>
                        </ul>

                        <h5 class="font-semibold mb-2 mt-4 text-purple-700">🎴 Particularité du Jeu à 5 Joueurs : L'Appel du Roi</h5>
                        <div class="bg-purple-50 p-4 rounded-lg space-y-3">
                            <p class="text-gray-700">
                                À 5 joueurs, le preneur ne joue <strong>pas seul contre 4</strong> ! Il a un partenaire secret grâce au système de l'appel.
                            </p>
                            
                            <h6 class="font-semibold text-gray-900">Comment ça marche ?</h6>
                            <ol class="list-decimal list-inside space-y-2 text-gray-700 ml-2">
                                <li><strong>Avant d'avoir regardé le chien</strong>, le preneur annonce un Roi de son choix (par exemple "J'appelle le Roi de Cœur")</li>
                                <li>Le joueur qui possède ce Roi devient <strong>secrètement</strong> l'appelé (le partenaire du preneur)</li>
                                <li><strong>Personne ne sait qui est l'appelé</strong> (même pas le preneur !) jusqu'à ce que le Roi soit joué</li>
                                <li>Quand le Roi appelé est joué, l'identité de l'appelé est révélée</li>
                                <li>Le preneur et l'appelé jouent alors <strong>ensemble</strong> contre les 3 autres joueurs</li>
                            </ol>

                            <h6 class="font-semibold text-gray-900 mt-3">Cas particuliers :</h6>
                            <ul class="list-disc list-inside space-y-2 text-gray-700 ml-2">
                                <li><strong>Le preneur a le Roi appelé :</strong> Il joue seul contre 4 (il le saura en regardant son jeu)</li>
                                <li><strong>Le Roi est au chien :</strong> Le preneur joue aussi seul contre 4 (il le saura en retournant le chien)</li>
                                <li><strong>Quel Roi appeler ?</strong> Généralement, appelez le Roi d'une couleur où vous êtes faible pour que votre partenaire puisse vous aider</li>
                            </ul>

                            <h6 class="font-semibold text-gray-900 mt-3">Stratégie pour l'appelé :</h6>
                            <ul class="list-disc list-inside space-y-2 text-gray-700 ml-2">
                                <li>💡 Ne révélez pas votre identité trop tôt : faites semblant d'être en défense</li>
                                <li>💡 Mettez des points dans les plis du preneur, pas dans ceux des défenseurs</li>
                                <li>💡 Aidez le preneur à protéger ses bouts et à contrôler le jeu</li>
                            </ul>

                            <p class="text-sm bg-purple-100 p-3 rounded mt-3">
                                <strong>💡 Astuce :</strong> Le système d'appel rend le jeu à 5 très tactique ! L'appelé doit jouer finement pour 
                                ne pas se faire repérer trop tôt tout en aidant discrètement le preneur.
                            </p>
                        </div>
                    </div>

                    <!-- Comptage des points -->
                    <div class="mb-8 border-l-4 border-blue-500 pl-4">
                        <h4 class="text-xl font-bold mb-3">🔢 Comptage des Points</h4>
                        
                        <h5 class="font-semibold mb-2">Valeur des cartes :</h5>
                        <ul class="list-disc list-inside space-y-1 text-gray-700 mb-4">
                            <li><strong>Bouts (1, 21, Excuse)</strong> : 4,5 points chacun</li>
                            <li><strong>Roi</strong> : 4,5 points</li>
                            <li><strong>Dame</strong> : 3,5 points</li>
                            <li><strong>Cavalier</strong> : 2,5 points</li>
                            <li><strong>Valet</strong> : 1,5 points</li>
                            <li><strong>Autres cartes</strong> : 0,5 point</li>
                        </ul>

                        <p class="mb-2 text-gray-700"><strong>Total du jeu : 91 points</strong></p>

                        <h5 class="font-semibold mb-2 mt-4">Seuils à atteindre pour gagner :</h5>
                        <div class="bg-blue-50 p-4 rounded">
                            <ul class="space-y-2">
                                <li>✅ <strong>Avec 0 bout</strong> : 56 points minimum</li>
                                <li>✅ <strong>Avec 1 bout</strong> : 51 points minimum</li>
                                <li>✅ <strong>Avec 2 bouts</strong> : 41 points minimum</li>
                                <li>✅ <strong>Avec 3 bouts</strong> : 36 points minimum</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Les bonus -->
                    <div class="mb-8 border-l-4 border-purple-500 pl-4">
                        <h4 class="text-xl font-bold mb-3">🎁 Les Bonus</h4>
                        
                        <ul class="space-y-3 text-gray-700">
                            <li>
                                <strong>🎯 Petit au bout (+10 points)</strong><br>
                                Si le petit (atout 1) est joué au dernier pli et qu'on le remporte
                            </li>
                            <li>
                                <strong>🖐️ Poignée</strong><br>
                                • Simple (10 atouts) : +20 points<br>
                                • Double (13 atouts) : +30 points<br>
                                • Triple (15 atouts) : +40 points<br>
                                <span class="text-sm italic">À annoncer avant de jouer sa première carte</span>
                            </li>
                            <li>
                                <strong>👑 Chelem (tous les plis)</strong><br>
                                • Annoncé et réussi : +400 points<br>
                                • Non annoncé mais réussi : +200 points<br>
                                • Annoncé mais raté : -200 points
                            </li>
                            <li>
                                <strong>💀 Misère (+10 points par joueur)</strong><br>
                                • De têtes : aucune tête (Roi, Dame, Cavalier, Valet)<br>
                                • D'atouts : aucun atout
                            </li>
                        </ul>
                    </div>

                    <!-- Calcul du score -->
                    <div class="mb-8 border-l-4 border-red-500 pl-4">
                        <h4 class="text-xl font-bold mb-3">📊 Calcul du Score Final</h4>
                        
                        <div class="bg-gray-50 p-4 rounded mb-4">
                            <p class="font-mono text-sm mb-2">Score = (25 + écart) × multiplicateur du contrat</p>
                            <p class="text-sm text-gray-600">
                                <strong>Écart</strong> = différence entre vos points et le seuil<br>
                                <strong>Multiplicateur</strong> : Petite ×1, Garde ×2, Garde sans ×4, Garde contre ×6
                            </p>
                        </div>

                        <p class="font-semibold mb-2">Répartition :</p>
                        <ul class="list-disc list-inside space-y-1 text-gray-700">
                            <li><strong>À 4 joueurs</strong> : le preneur gagne/perd ×3, chaque défenseur ×1</li>
                            <li><strong>À 5 joueurs avec appelé</strong> : preneur ×2, appelé ×1, chaque défenseur ×1</li>
                            <li><strong>À 5 joueurs sans appelé</strong> : preneur ×4, chaque défenseur ×1</li>
                        </ul>
                    </div>

                    <!-- Conseils -->
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-6 border-l-4 mb-8">
                        <h4 class="text-xl font-bold mb-3">💡 Conseils pour Débuter</h4>
                        <ul class="space-y-2 text-gray-700 mb-4">
                            <li>✓ Comptez vos atouts avant de prendre </li>
                            <li>✓ Gardez vos bouts précieusement</li>
                            <li>✓ Faites attention au petit, il est fragile ! Jouez-le quand vous êtes sûr de faire le pli</li>
                            <li>✓ N'oubliez pas : l'atout est roi !</li>
                        </ul>

                        <h4 class="text-xl font-bold mb-3">🎓 Conseils Stratégiques</h4>
                        
                        <h5 class="font-semibold mb-2 text-indigo-700">Pour le Preneur :</h5>
                        <ul class="space-y-2 text-gray-700 mb-4">
                            <li>🎯 <strong>Comptez les atouts :</strong> Il y a 22 atouts au total (21 + Excuse), sachez combien il en reste</li>
                            <li>🎯 <strong>Protégez vos bouts :</strong> Le Petit meurt facilement, le 21 et l'Excuse sont plus sûrs</li>
                            <li>🎯 <strong>Écart intelligent :</strong> Soyez stratégiques sur les cartes que vous retirez ! Sauvez des points si besoin et essayez de faire des coupes si vous avez beaucoup d'atout</li>
                            <li>🎯 <strong>Faites des coupes :</strong> Si vous n'avez pas une couleur, c'est une opportunité de prendre des points</li>
                            <li>🎯 <strong>Le Petit au bout :</strong> Si vous avez le Petit, essayez de le jouer au dernier pli quand vous maîtrisez le jeu (attention il faut avoir compter les atouts pour être surs)</li>
                        </ul>

                        <h5 class="font-semibold mb-2 text-red-700">Pour la Défense :</h5>
                        <ul class="space-y-2 text-gray-700 mb-4">
                            <li>🛡️ <strong>Gardez vos atouts :</strong> Ne vous défaussez pas trop vite de vos atouts</li>
                            <li>🛡️ <strong>Longues couleurs :</strong> Si vous êtes long dans une couleur, jouez-la pour faire couper le preneur. Attention tout de même à ne pas faire perdre des points à votre équipe !</li>
                            <li>🛡️ <strong>Communication :</strong> Défaussez les points (Rois, Dames) dans les plis de vos partenaires</li>
                            <li>🛡️ <strong>Atouts maîtres :</strong> Si vous avez le 21 ou des gros atouts, gardez-les pour le bon moment</li>
                        </ul>

                        <h5 class="font-semibold mb-2 text-green-700">Conseils Généraux :</h5>
                        <ul class="space-y-2 text-gray-700">
                            <li>🧠 <strong>Mémorisation :</strong> Essayez de retenir quelles couleurs les autres joueurs n'ont plus</li>
                            <li>🧠 <strong>L'Excuse :</strong> Utilisez-la stratégiquement pour sauver des points ou des atouts</li>
                            <li>🧠 <strong>La chasse au petit :</strong> Essayez de définir qui à le petit et s'il est possible de le voler</li>
                            <li>🧠 <strong>Pratique :</strong> Le tarot s'apprend en jouant, n'ayez pas peur de faire des erreurs !</li>
                            <li>🧠 <strong>Patience :</strong> Les premiers jeux peuvent sembler complexes, mais ça devient vite naturel</li>
                        </ul>
                    </div>

                    <!-- FAQ -->
                    <div class="mb-8 border-l-4 border-orange-500 pl-4">
                        <h4 class="text-xl font-bold mb-3">❓ Foire Aux Questions (FAQ)</h4>
                        
                        <div class="space-y-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h5 class="font-semibold text-gray-900 mb-2">Q : Que faire si j'ai uniquement des atouts et pas de couleur ?</h5>
                                <p class="text-gray-700">R : Vous devez jouer un atout, même si c'est à votre désavantage. Essayez de jouer un petit atout pour en garder des gros pour plus tard.</p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h5 class="font-semibold text-gray-900 mb-2">Q : Peut-on écarter un bout dans le chien ?</h5>
                                <p class="text-gray-700">R : Non ! Les 3 bouts (Petit, 21, Excuse) ne peuvent JAMAIS être écartés.</p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h5 class="font-semibold text-gray-900 mb-2">Q : L'Excuse remporte-t-elle le pli ?</h5>
                                <p class="text-gray-700">R : Non, jamais. L'Excuse permet juste de ne pas suivre les règles de coupe, mais elle ne gagne jamais le pli.</p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h5 class="font-semibold text-gray-900 mb-2">Q : Que se passe-t-il si je n'ai aucun atout pour monter ?</h5>
                                <p class="text-gray-700">R : Vous devez quand même jouer un atout (on dit "sous-couper"). Ce n'est que si vous n'avez ni la couleur ni aucun atout que vous pouvez vous défausser.</p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h5 class="font-semibold text-gray-900 mb-2">Q : Combien d'atouts faut-il avoir pour prendre ?</h5>
                                <p class="text-gray-700">R : Tout dépend du nombre de joueur. Comptez 22 / par le nombre de joueur, si vous avez plus d'atout que ça, vous avez donc plus d'atout que la moyenne. Ensuite, le plus important pour prendre c'est surtout les bouts (car ça indique le nombre de points à réaliser) !</p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h5 class="font-semibold text-gray-900 mb-2">Q : Peut-on écarter des atouts ?</h5>
                                <p class="text-gray-700">R : Oui, mais seulement si vous ne pouvez pas faire autrement (par exemple si vous avez trop d'atouts). Il faut les montrer aux autres joueurs avant de les écarter.</p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h5 class="font-semibold text-gray-900 mb-2">Q : Comment fonctionne le Petit au bout ?</h5>
                                <p class="text-gray-700">R : Si le Petit (atout 1) est joué au tout dernier pli, celui qui le fait gagner (ou perdre) marque un bonus (ou malus) de 10 points supplémentaires × le multiplicateur du contrat.</p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h5 class="font-semibold text-gray-900 mb-2">Q : Quelle est la différence entre Garde Sans et Garde Contre ?</h5>
                                <p class="text-gray-700">R : En Garde Sans (×4), le preneur ne prend pas le chien mais les points du chien sont pour lui. En Garde Contre (×6), le chien va aux adversaires ! C'est très risqué.</p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h5 class="font-semibold text-gray-900 mb-2">Q : À 5 joueurs, qui est l'appelé ?</h5>
                                <p class="text-gray-700">R : Le preneur appelle un Roi de son choix. Le joueur qui a ce Roi devient secrètement son partenaire (il ne se révèle que quand il joue ce Roi).</p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h5 class="font-semibold text-gray-900 mb-2">Q : Que signifie "pisser" ?</h5>
                                <p class="text-gray-700">R : C'est le terme pour se défausser d'une carte quand on n'a ni la couleur demandée, ni d'atout. On "pisse" généralement une petite carte sans valeur.</p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h5 class="font-semibold text-gray-900 mb-2">Q : Les misères sont-elles obligatoires ?</h5>
                                <p class="text-gray-700">R : Non, les misères (zéro têtes ou zéro atouts) sont des annonces optionnelles. Annoncez-le avant le jeu pour gagner des points bonus !</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-6 rounded-lg">
                        <p>Et en vidéo ça donne quoi  : <a href="https://www.youtube.com/watch?v=saLfnQjpfPk" style="color : aqua">Vidéo règle tarot</a></p>
                    </div>

                    <div class="mt-8 text-center">
                        <a href="{{ route('games.create') }}" 
                            class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700">
                            🎴 Commencer une partie
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

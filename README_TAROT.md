# 🃏 Application de Gestion de Tarot avec ELO

Application web Laravel pour gérer les parties de tarot et calculer automatiquement les scores ELO des joueurs.

## 🎯 Fonctionnalités

- **Authentification des utilisateurs** avec Laravel Breeze
- **Enregistrement des parties** de tarot avec calcul automatique des scores
- **Système de classement ELO** pour suivre la progression des joueurs
- **Historique complet** des parties jouées
- **Détails des parties** avec évolution ELO de chaque joueur

## 📊 Calcul des Scores

### Score de Partie
Le score est calculé selon les règles officielles du tarot :
- Score de base : 25 points + différence par rapport au seuil
- Seuils selon les bouts : 0 bout = 56 pts, 1 bout = 51 pts, 2 bouts = 41 pts, 3 bouts = 36 pts
- Multiplicateur selon le contrat :
  - Petite : x1
  - Garde : x2
  - Garde Sans : x4
  - Garde Contre : x6

### Système ELO
Le système ELO est utilisé pour classer les joueurs :
- ELO de départ : 1200
- Facteur K : 32 (volatilité standard)
- Les attaquants gagnent/perdent des points ELO face aux défenseurs
- Le changement d'ELO dépend de la différence d'ELO entre les équipes

## 🚀 Installation

### Prérequis
- PHP 8.2 ou supérieur
- Composer
- MySQL ou SQLite
- Node.js et NPM

### Étapes d'installation

1. **Configurer la base de données**
   Éditez le fichier `.env` et configurez votre base de données :
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=tarot
   DB_USERNAME=root
   DB_PASSWORD=
   ```

2. **Générer la clé d'application**
   ```bash
   php artisan key:generate
   ```

3. **Exécuter les migrations**
   ```bash
   php artisan migrate
   ```

4. **Installer et compiler les assets**
   ```bash
   npm install
   npm run build
   ```

5. **Lancer le serveur de développement**
   ```bash
   php artisan serve
   ```

6. **Accéder à l'application**
   Ouvrez votre navigateur à l'adresse : `http://localhost:8000`

## 📁 Structure du Projet

### Modèles
- `User` : Utilisateur avec ELO et nombre de parties jouées
- `Game` : Partie de tarot avec contrat, points, bouts, etc.
- `GamePlayer` : Relation entre une partie et un joueur (score, changement ELO)

### Services
- `TarotScoreService` : Calcul des scores et mise à jour des ELO

### Contrôleurs
- `GameController` : Gestion des parties (création, affichage, historique)

### Routes
- `/` : Redirection vers le classement
- `/leaderboard` : Classement ELO (public)
- `/games` : Historique des parties (authentification requise)
- `/games/create` : Nouvelle partie (authentification requise)
- `/games/{game}` : Détails d'une partie (authentification requise)

## 🎮 Utilisation

1. **Créez un compte** ou connectez-vous
2. **Enregistrez une partie** en indiquant :
   - Date et heure
   - Type de contrat
   - Points du preneur
   - Nombre de bouts
   - Réussite ou échec du contrat
   - Points bonus éventuels
   - Les joueurs (preneur, attaquant optionnel, 3 défenseurs)
3. **Consultez le classement** pour voir l'évolution des ELO
4. **Accédez à l'historique** pour revoir les parties passées

## 🔧 Personnalisation

### Modifier le facteur K de l'ELO
Dans `app/Services/TarotScoreService.php`, méthode `calculateEloChange()` :
```php
public function calculateEloChange(int $playerElo, int $opponentElo, float $score, int $kFactor = 32)
```

### Modifier l'ELO de départ
Dans la migration `add_elo_to_users_table.php` :
```php
$table->integer('elo')->default(1200);
```

## 📝 Base de Données

### Tables
- `users` : Informations utilisateurs + ELO
- `games` : Parties jouées
- `game_players` : Relation entre parties et joueurs avec scores et ELO

## 🤝 Contribution

Les contributions sont les bienvenues ! N'hésitez pas à ouvrir une issue ou une pull request.

## 📄 Licence

Ce projet est open source et disponible sous la licence MIT.

## 🎉 Crédits

Développé avec Laravel 12 et Tailwind CSS.

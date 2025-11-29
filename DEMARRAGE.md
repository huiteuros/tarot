# 🚀 Démarrage Rapide - Application Tarot

## 🎮 Accès à l'Application

Le serveur est démarré sur : **http://127.0.0.1:8000**

## 👤 Comptes de Test

Utilisez ces comptes pour vous connecter (mot de passe : `password`) :

- alice@tarot.test
- bob@tarot.test
- charlie@tarot.test
- david@tarot.test
- emma@tarot.test

## 📝 Comment Utiliser l'Application

### 1. Créer un Compte ou Se Connecter
- Allez sur http://127.0.0.1:8000
- Cliquez sur "Register" pour créer un compte ou "Login" avec un compte de test
- **Email** : alice@tarot.test  
- **Password** : password

### 2. Voir le Classement
- Page d'accueil affiche le classement ELO de tous les joueurs
- Les 3 premiers ont des médailles 🥇🥈🥉

### 3. Enregistrer une Partie
- Cliquez sur "➕ Nouvelle Partie"
- Remplissez le formulaire :
  - **Date et Heure** : Date de la partie
  - **Type de Contrat** : Petite, Garde, Garde Sans, ou Garde Contre
  - **Points du Preneur** : Points marqués (0-91)
  - **Nombre de Bouts** : 0, 1, 2 ou 3 bouts
  - **Contrat Réussi** : ✅ Réussi ou ❌ Chuté
  - **Points Bonus** : Pour poignée, petit au bout, chelem...
  - **Preneur** : Le joueur qui a pris
  - **Attaquant** : Optionnel pour partie à 5
  - **Défenseurs** : Cochez 3 défenseurs

### 4. Voir l'Historique
- Cliquez sur "📋 Historique"
- Vous voyez toutes les parties jouées
- Cliquez sur "Détails →" pour voir le détail d'une partie

### 5. Consulter une Partie
- Affiche tous les détails : contrat, points, bouts
- Montre l'évolution ELO de chaque joueur
- Score de chaque joueur avec changement ELO

## 🎯 Exemple de Partie à Enregistrer

**Scénario** : Alice (preneur) réussit une Petite avec 2 bouts et 45 points

1. Date : Aujourd'hui
2. Contrat : Petite
3. Points : 45
4. Bouts : 2
5. Réussi : ✅
6. Bonus : 0
7. Preneur : Alice Martin
8. Défenseurs : Bob, Charlie, David

**Résultat** :
- Alice gagne des points et son ELO augmente
- Bob, Charlie, David perdent des points et leur ELO diminue

## 🎲 Règles de Calcul

### Score de Base
- Seuil selon bouts : 0→56pts, 1→51pts, 2→41pts, 3→36pts
- Base = 25 + (points - seuil)
- Multiplicateur : Petite=1x, Garde=2x, Garde Sans=4x, Garde Contre=6x

### ELO
- Départ : 1200
- Volatilité (K) : 32
- Les gagnants prennent des points ELO aux perdants
- Plus la différence d'ELO est grande, plus le changement est faible pour le favori

## 🛠️ Commandes Utiles

### Arrêter le Serveur
- Dans le terminal où le serveur tourne : `Ctrl+C`

### Redémarrer le Serveur
```bash
php artisan serve
```

### Ajouter des Utilisateurs
```bash
# Ouvrez la console Tinker
php artisan tinker

# Créez un utilisateur
User::create(['name' => 'Votre Nom', 'email' => 'email@test.fr', 'password' => Hash::make('password'), 'elo' => 1200, 'games_played' => 0]);
```

### Réinitialiser la Base de Données
```bash
php artisan migrate:fresh --seed
```

## 📊 Structure de l'Application

- **Page d'accueil** : Classement ELO
- **Inscription/Connexion** : Laravel Breeze
- **Nouvelle Partie** : Formulaire d'enregistrement
- **Historique** : Liste de toutes les parties
- **Détails** : Analyse complète d'une partie

## 💡 Astuces

- Le système calcule automatiquement les scores selon les règles officielles
- L'ELO est mis à jour instantanément après chaque partie
- Vous pouvez jouer à 4 (sans attaquant) ou à 5 (avec attaquant)
- Le classement est visible même sans être connecté

## 🎉 Bon Jeu !

Amusez-vous bien à enregistrer vos parties de tarot ! 🃏

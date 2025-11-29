# 🃏 Application de Gestion de Parties de Tarot<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>



Application web pour gérer vos parties de tarot et suivre le classement des joueurs.<p align="center">

<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>

## 🎯 Fonctionnalités<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>

<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>

- 📊 **Classement ELO** des joueurs<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>

- 🎴 **Enregistrement des parties** (4 ou 5 joueurs)</p>

- 📈 **Historique détaillé** de toutes les parties

- 🏆 **Calcul automatique des scores** selon les règles officielles du tarot## About Laravel

- 💀 **Gestion des bonus** : petit au bout, poignées, chelem, misères

- 🔄 **Rejouer avec les mêmes joueurs** en un clicLaravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- ⚙️ **Administration** pour gérer les comptes utilisateurs

- [Simple, fast routing engine](https://laravel.com/docs/routing).

## 🚀 Installation- [Powerful dependency injection container](https://laravel.com/docs/container).

- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.

### Prérequis- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).

- Database agnostic [schema migrations](https://laravel.com/docs/migrations).

- PHP 8.2+- [Robust background job processing](https://laravel.com/docs/queues).

- MySQL 5.7+ / MariaDB 10.3+- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

- Composer

- Node.js & npmLaravel is accessible, powerful, and provides tools required for large, robust applications.



### Étapes d'installation## Learning Laravel



1. **Cloner le projet**Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

```bash

git clone <url-du-repo>If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

cd tarot

```## Laravel Sponsors



2. **Installer les dépendances**We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

```bash

composer install### Premium Partners

npm install

npm run build- **[Vehikl](https://vehikl.com)**

```- **[Tighten Co.](https://tighten.co)**

- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**

3. **Configuration**- **[64 Robots](https://64robots.com)**

```bash- **[Curotec](https://www.curotec.com/services/technologies/laravel)**

cp .env.example .env- **[DevSquad](https://devsquad.com/hire-laravel-developers)**

php artisan key:generate- **[Redberry](https://redberry.international/laravel-development)**

```- **[Active Logic](https://activelogic.com)**



4. **Configurer la base de données**## Contributing



Éditez le fichier `.env` et configurez vos informations MySQL :Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

```env

DB_CONNECTION=mysql## Code of Conduct

DB_HOST=127.0.0.1

DB_PORT=3306In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

DB_DATABASE=tarot

DB_USERNAME=root## Security Vulnerabilities

DB_PASSWORD=

```If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.



5. **Créer la base de données**## License

```bash

mysql -u root -e "CREATE DATABASE tarot CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

```

6. **Exécuter les migrations**
```bash
php artisan migrate
php artisan db:seed
```

7. **Lancer le serveur**
```bash
php artisan serve
```

L'application est maintenant accessible sur http://localhost:8000

## 👥 Comptes de test

Après le seeding, vous pouvez vous connecter avec :
- **Email** : alice@tarot.test
- **Mot de passe** : password

Autres comptes disponibles : bob@tarot.test, charlie@tarot.test, david@tarot.test, eve@tarot.test

## 📋 Utilisation

### Enregistrer une partie

1. Connectez-vous
2. Cliquez sur "➕ Nouvelle Partie"
3. Sélectionnez 4 ou 5 joueurs
4. Choisissez le preneur et l'appelé (si 5 joueurs)
5. Saisissez le contrat, les points et les bouts
6. Ajoutez les bonus éventuels
7. Le résultat est calculé automatiquement !

### Calculer les scores

Les scores sont calculés automatiquement selon les règles officielles du tarot :
- **Seuils** : 0 bout = 56 pts, 1 bout = 51 pts, 2 bouts = 41 pts, 3 bouts = 36 pts
- **Formule** : (25 + écart) × multiplicateur du contrat
- **Répartition** :
  - À 4 joueurs : preneur ×3, chaque défenseur ×1
  - À 5 joueurs avec appelé : preneur ×2, appelé ×1, chaque défenseur ×1
  - À 5 joueurs sans appelé : preneur ×4, chaque défenseur ×1
- **Bonus** : petit au bout, poignées, chelem, misères (têtes/atouts)

### Gérer les utilisateurs

1. Connectez-vous
2. Allez dans "⚙️ Administration"
3. Vous pouvez :
   - Créer de nouveaux joueurs
   - Modifier les comptes existants
   - Supprimer des joueurs (sauf vous-même)

## 🛠️ Technologies utilisées

- **Backend** : Laravel 11
- **Frontend** : Blade, Alpine.js, Tailwind CSS
- **Base de données** : MySQL
- **Authentification** : Laravel Breeze

## 📝 Licence

Ce projet est open-source.

## 🤝 Contribution

Les contributions sont les bienvenues ! N'hésitez pas à ouvrir une issue ou une pull request.

---

Bon jeu ! 🎴

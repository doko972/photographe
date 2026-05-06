# Kenaurelie — Concours Photos Viking

Application web pour le concours de costumes de l'anniversaire 40 ans de Florent & Aurélie, sur le thème Viking.

## Fonctionnalités

- **Galerie publique** — photos approuvées, tri par date ou popularité, lightbox, pagination
- **Concours photos** — upload de costume, miniature automatique (600px JPEG), validation admin avant publication
- **Likes / Favoris** — ajout aux favoris en AJAX
- **Votes** — classement de ses 10 photos préférées (rang 1 à 10), score calculé (`11 − rang`)
- **Podium** — classement public des photos par score de vote
- **Panel admin** — validation/rejet des photos, liste des utilisateurs
- **Authentification complète** — inscription, connexion, profil viking, mot de passe, suppression de compte

## Stack technique

| Couche | Technologie |
|--------|-------------|
| Framework | Laravel 13 + Breeze |
| PHP | ^8.3 |
| Base de données | MySQL |
| CSS | SASS (Dart Sass 3, `@use` / `@forward`) |
| JS | Vanilla JS (modules ES) |
| Build | Vite 8 |
| Images | Intervention Image v4 (driver GD) |

## Installation locale

```bash
# 1. Cloner le dépôt
git clone https://github.com/votre-user/kenaurelie.git
cd kenaurelie

# 2. Dépendances
composer install
npm install

# 3. Configuration
cp .env.example .env
# Éditez .env : DB_DATABASE, DB_USERNAME, DB_PASSWORD, APP_URL
php artisan key:generate

# 4. Base de données
php artisan migrate

# 5. Données de démonstration (optionnel)
php artisan db:seed

# 6. Assets et stockage
php artisan storage:link
npm run build
```

Accès local : `http://localhost/kenaurelie/public`

## Déploiement (hébergement mutualisé sans Node.js)

```bash
composer install --no-dev --optimize-autoloader
cp .env.example .env
# Éditez .env : APP_ENV=production, APP_DEBUG=false, APP_URL, DB_*
php artisan key:generate
php artisan migrate --force
ln -s $(pwd)/storage/app/public $(pwd)/public/storage
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

> Les assets compilés (`public/build/`) sont inclus dans le dépôt — aucun Node.js requis sur le serveur.

## Comptes de démonstration (seeder)

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Admin | admin@kenaurelie.fr | admin1234 |
| Utilisateur | user1@kenaurelie.fr | password |

Le panel admin est accessible via `/admin`.

## Structure du projet

```
app/
├── Http/Controllers/
│   ├── Admin/AdminController.php
│   ├── GalleryController.php
│   ├── LikeController.php
│   ├── PhotoController.php
│   └── VoteController.php
├── Models/            # User, Photo, Like, Vote
└── Policies/          # PhotoPolicy

resources/
├── sass/
│   ├── _variables.scss
│   ├── _shared.scss   # @forward pour tous les composants
│   └── components/    # Un fichier par composant UI
└── js/
    └── components/    # gallery.js, vote.js, carousel.js…
```

## Licence

Projet privé — usage personnel.

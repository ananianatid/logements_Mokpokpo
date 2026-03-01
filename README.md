# Mokpokpo Logements - Gestion de Résidences Universitaires

Application de gestion automatisée des contrats de logement, des états des lieux et des paiements pour l'Université Mokpokpo.

## Fonctionnalités Clés

- **Gestion des Demandes** : Workflow complet de la demande à l'attribution.
- **Automatisation des Contrats** : Génération automatique des contrats et calcul des arriérés.
- **Installation Organique** : Assignation automatique des concierges et gestion de l'état des lieux.
- **Espace Étudiant** : Dashboard moderne avec suivi de l'activation et historique des paiements.

---

## Déploiement sur cPanel (Hébergement Mutualisé)

### 1. Cloner le projet via le terminal SSH

```bash
git clone https://github.com/ananianatid/logements_Mokpokpo.git
cd logements_Mokpokpo
```

### 2. Installer les dépendances

```bash
composer install --no-dev --optimize-autoloader
```

### 3. Configurer l'environnement

```bash
cp .env.example .env
```

Éditer ensuite le fichier `.env` avec ces valeurs de production :

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com

DB_CONNECTION=sqlite
# ou configurez MySQL si vous utilisez une BDD cPanel :
# DB_CONNECTION=mysql
# DB_HOST=localhost
# DB_PORT=3306
# DB_DATABASE=nom_de_votre_bdd
# DB_USERNAME=votre_utilisateur
# DB_PASSWORD=votre_mot_de_passe
```

Puis générez la clé :

```bash
php artisan key:generate
```

### 4. Préparer la base de données

```bash
# Si SQLite :
touch database/database.sqlite

php artisan migrate --seed --force
```

### 5. Adapter les permissions du serveur

```bash
chmod -R 775 storage bootstrap/cache
```

### 6. Lier le stockage public

```bash
php artisan storage:link
```

### 7. Configurer le Document Root dans cPanel

> **⚠️ Important** : Dans cPanel, le **Document Root** de votre domaine doit pointer vers le dossier `public/` du projet, **pas** la racine.

- Allez dans **Domaines → Domaines** (ou Sous-domaines).
- Changez le Document Root en : `logements_Mokpokpo/public`

### 8. Optimiser pour la production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Déploiement Local (Développement)

### Pré-requis

- PHP 8.2+, Composer, SQLite, Node.js & NPM

### Étapes rapides

```bash
git clone https://github.com/ananianatid/logements_Mokpokpo.git
cd logements_Mokpokpo
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
php artisan serve --port=8080
```

L'application sera accessible sur `http://localhost:8080`.

---

## Administration

- **URL Panel Admin** : `http://votre-domaine.com/admin`
- Les accès administrateurs sont générés automatiquement via les seeders.

## Technologies Utilisées

- **Laravel 11/12**, **Filament PHP**, **Livewire**, **Tailwind CSS**, **SQLite/MySQL**



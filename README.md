# Mokpokpo Logements - Gestion de Résidences Universitaires

Application de gestion automatisée des contrats de logement, des états des lieux et des paiements pour l'Université Mokpokpo.

## Fonctionnalités Clés

- **Gestion des Demandes** : Workflow complet de la demande à l'attribution.
- **Automatisation des Contrats** : Génération automatique des contrats et calcul des arriérés.
- **Installation Organique** : Assignation automatique des concierges et gestion de l'état des lieux.
- **Espace Étudiant** : Dashboard moderne avec suivi de l'activation et historique des paiements.

## Déploiement Local

### Pré-requis

- PHP 8.2+
- Composer
- SQLite (ou autre base de données supportée)
- Node.js & NPM (pour les assets)

### Étapes d'installation

1. **Cloner le projet**
   ```bash
   git clone <repository-url>
   cd logements_Mokpokpo
   ```

2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

3. **Configurer l'environnement**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Note: Assurez-vous que `DB_CONNECTION=sqlite` est configuré dans le `.env` pour une installation rapide.*

4. **Préparer la base de données**
   ```bash
   touch database/database.sqlite
   php artisan migrate --seed
   ```

5. **Lier le stockage**
   ```bash
   php artisan storage:link
   ```

6. **Lancer le serveur de développement**
   ```bash
   php artisan serve --port=8080
   ```
   L'application sera accessible sur `http://localhost:8080`.

## Administration

Le panel d'administration est propulsé par **Filament v3**.
- URL : `http://localhost:8080/admin`
- Les accès administrateurs sont générés via les seeders.

## Technologies Utilisées

- **Laravel 11/12**
- **Filament PHP** (Admin Panel)
- **Livewire**
- **Tailwind CSS**


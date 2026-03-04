# Mokpokpo Logements - Gestion de Résidences Universitaires

Application de gestion automatisée des contrats de logement, des états des lieux et des paiements pour l'Université Mokpokpo.

## 🚀 Guide d'installation de A à Z (Windows & Mac)
Ce guide est conçu pour un ordinateur (Windows ou Mac) fraîchement réinitialisé. Suivez ces étapes pas-à-pas pour installer absolument tous les outils nécessaires et lancer le projet avec succès.

---

### Partie 1 : Installation des outils indispensables

Vérifiez que vous installez bien les outils suivants avant de commencer.

#### 🍎 Pour les utilisateurs Mac
1. **Laravel Herd** (Recommandé) : Téléchargez et installez [Laravel Herd](https://herd.laravel.com/). C'est un environnement de développement tout-en-un et simplissime pour Mac qui installe automatiquement la bonne version de **PHP** et **Composer**.
2. **Node.js** : Téléchargez l'installateur "LTS" (Long Term Support) sur [nodejs.org](https://nodejs.org/) et installez-le.
3. **Git** : Ouvrez l'application **Terminal** (recherchez "Terminal" dans Spotlight) et tapez `git --version`. Si une fenêtre vous propose d'installer les outils de développement (Command Line Tools), acceptez l'installation.

#### 🪟 Pour les utilisateurs Windows
1. **Laragon** (Recommandé) : Téléchargez et installez [Laragon Full](https://laragon.org/download/index.html). Cela installera vos environnements **PHP** et une base de données MySQL.
2. **Composer** : Téléchargez et installez [Composer-Setup.exe](https://getcomposer.org/download/). Lors de l'installation, il vous demandera le chemin de l'exécutable PHP (pour Laragon, c'est généralement : `C:\laragon\bin\php\php-X.X\php.exe`).
3. **Node.js** : Téléchargez l'installateur "LTS" (Long Term Support) sur [nodejs.org](https://nodejs.org/) et installez-le.
4. **Git** : Téléchargez et installez [Git for Windows](https://gitforwindows.org/). Cela installera "**Git Bash**", l'application terminal recommandée (bien plus performante que le CMD classique) pour taper vos commandes sous Windows.

---

### Partie 2 : Mise en place du projet

Ouvrez votre terminal (**Terminal** sur Mac, ou **Git Bash** sur Windows).

#### 1. Récupérer le code source
Placez-vous dans le dossier où vous souhaitez installer le projet (par exemple votre dossier de documents) et tapez cette commande :
```bash
git clone https://github.com/ananianatid/logements_Mokpokpo.git
cd logements_Mokpokpo
```

#### 2. Installer les dépendances du projet
Une fois dans le dossier du projet, il faut télécharger les paquets PHP (via Composer) et JavaScript (via NPM) :
```bash
composer install
npm install
```

#### 3. Configurer l'environnement (.env)
Copiez le fichier de configuration "exemple" pour en faire un fichier réel de paramètres propres à votre machine.
- Sur Mac ou avec Git Bash (Windows) :
  ```bash
  cp .env.example .env
  ```
- Si vous utilisez l'invite de commande CMD normale (Windows) :
  ```cmd
  copy .env.example .env
  ```

Générez ensuite la clé de sécurité cryptographique de l'application :
```bash
php artisan key:generate
```

#### 4. Préparer la base de données
Par défaut, ce projet est configuré pour utiliser **SQLite** en local, ce qui signifie que vous n'avez pas besoin d'installer de serveur de base de données lourd !

Créez simplement le fichier vide qui fera office de base de données en tapant (sur Mac ou Git bash) :
```bash
touch database/database.sqlite
```
*(Sous CMD Windows classique, tapez : `type NUL > database\database.sqlite`)*

Ensuite, lancez les migrations et remplissez la base de données avec des informations de test (le "seeding") :
```bash
php artisan migrate --seed
```

#### 5. Lier le stockage (Images, documents)
Pour que les photos des états des lieux ou les documents PDF générés soient disponibles à l'affichage :
```bash
php artisan storage:link
```

---

### Partie 3 : Lancer l'application

Pour faire fonctionner le projet pendant que vous travaillez, vous aurez besoin d'exécuter **deux processus**. L'idéal est donc d'ouvrir **deux fenêtres de terminal** en même temps (chacune positionnée dans le dossier `logements_Mokpokpo`).

**Dans le Terminal 1 (pour lancer le serveur PHP) :**
```bash
php artisan serve
```

**Dans le Terminal 2 (pour compiler le CSS/Javascript en temps réel) :**
```bash
npm run dev
```

---

### 🌐 Accéder à l'application via le Navigateur

Une fois les deux commandes précédentes en cours d'exécution :
- **Application Utilisateur / Étudiant** : allez sur [http://localhost:8000](http://localhost:8000)
- **Panel d'Administration (Directeurs/Concierges)** : allez sur [http://localhost:8000/admin](http://localhost:8000/admin)

*(Astuce : L'exécution de la commande de base de données à l'étape 4 génère des comptes "Tests". Vous pouvez vous connecter avec les identifiants présents, qui ont été créés automatiquement grâce aux "seeders").*

---

### 🛠 Technologies utilisées au sein du projet
- **Framework** : Laravel 12
- **Administration** : Filament PHP (v3)
- **Frontend** : Livewire 3 & Alpine.js
- **Design** : Tailwind CSS
- **Base de données** : SQLite (Dev) / MySQL (Prod)

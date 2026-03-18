# 🎓 Guide IA — Dashboard Étudiant : Application Mobile React Native
> **Destinataire :** Agent IA intégré à l'IDE (Cursor, Windsurf, Copilot Workspace…)  
> **Objectif :** Partir d'un projet React Native vierge (ou Expo) et implémenter l'interface **Dashboard Étudiant** complète, connectée à l'API Laravel existante.  
> **Backend :** `http://localhost:8000/api` (Laravel + Sanctum)  
> **Langue de l'UI :** Français  

---

## 📌 Table des matières

1. [Contexte du projet](#1-contexte-du-projet)
2. [Stack technique recommandée](#2-stack-technique-recommandée)
3. [Architecture des fichiers](#3-architecture-des-fichiers)
4. [Référence API complète](#4-référence-api-complète)
5. [Flux utilisateur (UX)](#5-flux-utilisateur-ux)
6. [Spécifications écran par écran](#6-spécifications-écran-par-écran)
7. [Design System (UI)](#7-design-system-ui)
8. [Gestion d'état et couche API](#8-gestion-détat-et-couche-api)
9. [Checklist d'implémentation](#9-checklist-dimplémentation)

---

## 1. Contexte du projet

L'application mobile permet à un étudiant de gérer sa **demande de logement** au sein de la résidence universitaire IP DEFITECH (Mokpokpo). Elle est la version mobile de l'interface admin Filament.

### Ce que peut faire un étudiant :
- Se connecter avec son email académique et son mot de passe
- Compléter son profil (requis avant toute demande)
- Soumettre une demande de logement
- Suivre le statut de sa demande
- Visualiser son contrat et son échéancier de paiement
- Signaler un incident technique dans son logement
- Se déconnecter

---

## 2. Stack technique recommandée

```
React Native (Expo SDK 51+)
├── expo-router          → Navigation (file-based)
├── @tanstack/react-query → Data fetching & caching
├── axios                → Client HTTP
├── zustand              → Store global (token auth)
├── react-native-async-storage → Persistance du token
├── react-native-reanimated    → Animations
├── @expo/vector-icons   → Icônes (Ionicons)
└── expo-secure-store    → Stockage sécurisé du token
```

**Commande d'initialisation :**
```bash
npx create-expo-app@latest MokpokpoStudent --template blank-typescript
cd MokpokpoStudent
npx expo install expo-router axios @tanstack/react-query zustand \
  @react-native-async-storage/async-storage expo-secure-store \
  react-native-reanimated @expo/vector-icons
```

---

## 3. Architecture des fichiers

```
MokpokpoStudent/
├── app/
│   ├── _layout.tsx              ← Root layout (QueryClient + AuthProvider)
│   ├── (auth)/
│   │   └── login.tsx            ← Écran de connexion
│   └── (student)/
│       ├── _layout.tsx          ← Tab navigator (protégé)
│       ├── index.tsx            ← Dashboard principal
│       ├── profile.tsx          ← Profil & complétion
│       ├── demandes.tsx         ← Mes demandes de logement
│       ├── contrat.tsx          ← Mon contrat & paiements
│       └── incidents/
│           ├── index.tsx        ← Liste des incidents
│           └── new.tsx          ← Signaler un incident
├── src/
│   ├── api/
│   │   ├── client.ts            ← Instance axios + interceptors
│   │   ├── auth.ts              ← login(), logout()
│   │   ├── dashboard.ts         ← getDashboard()
│   │   ├── profile.ts           ← getProfile(), updateProfile()
│   │   ├── demandes.ts          ← getDemandes(), storeDemande()
│   │   ├── contrat.ts           ← getContrat()
│   │   ├── incidents.ts         ← storeIncident()
│   │   └── reference.ts         ← getResidences(), getReferenceData()
│   ├── store/
│   │   └── authStore.ts         ← Zustand : token, user, isAuthenticated
│   ├── hooks/
│   │   ├── useDashboard.ts      ← useQuery wrapper
│   │   ├── useProfile.ts
│   │   ├── useDemandes.ts
│   │   └── useContrat.ts
│   ├── components/
│   │   ├── ui/
│   │   │   ├── Card.tsx
│   │   │   ├── Badge.tsx        ← Statut coloré
│   │   │   ├── ProgressBar.tsx
│   │   │   ├── EmptyState.tsx
│   │   │   └── LoadingSpinner.tsx
│   │   ├── dashboard/
│   │   │   ├── WelcomeHeader.tsx
│   │   │   ├── StatusCard.tsx   ← Carte demande/contrat
│   │   │   ├── PaymentTimeline.tsx
│   │   │   └── IncidentCard.tsx
│   │   └── forms/
│   │       ├── ProfileForm.tsx
│   │       └── DemandeForm.tsx
│   ├── types/
│   │   └── index.ts             ← Types TypeScript de toutes les entités
│   └── constants/
│       ├── colors.ts            ← Design tokens couleurs
│       └── theme.ts             ← Espacements, polices, rayons
```

---

## 4. Référence API complète

**Base URL :** `http://10.0.2.2:8000/api` (Android Emulator) | `http://localhost:8000/api` (iOS)  
**Auth :** `Authorization: Bearer {token}` (Sanctum Tokens)  
**Content-Type :** `application/json`

---

### 4.1 Authentification

#### `POST /student/login` *(public)*
```json
// Request
{ "email": "etudiant@ipdefitech.edu", "password": "password" }

// Response 200
{
  "token": "1|abc...",
  "user": {
    "id": 1,
    "email": "etudiant@ipdefitech.edu",
    "role": "etudiant",
    "nom": "Koné",
    "prenom": "Aminata",
    "profil_complet": false
  }
}

// Response 422
{ "message": "Les identifiants sont incorrects.", "errors": { "email": [...] } }
```

#### `POST /student/logout` *(auth requise)*
```json
// Response 200
{ "message": "Déconnecté avec succès." }
```

---

### 4.2 Dashboard (appel principal)

#### `GET /student/dashboard` *(auth requise)*
> **Appeler cet endpoint en premier** au chargement de l'app. Il retourne toutes les données nécessaires à l'affichage du dashboard en une seule requête.

```json
// Response 200 — profil incomplet
{
  "profil_complet": false,
  "demande": null,
  "contrat": null,
  "activation_progress": null,
  "incidents": [],
  "payment_history": []
}

// Response 200 — étudiant avec contrat actif
{
  "profil_complet": true,
  "etudiant": {
    "id": 3,
    "nom": "Koné",
    "prenom": "Aminata",
    "moyenne_bac": 14.5,
    "profil_complet": true
  },
  "demande": {
    "id": 7,
    "statut": "Validée",
    "date_soumission": "2025-09-01T08:00:00+00:00",
    "date_traitement": "2025-09-05T10:00:00+00:00",
    "priorite": 2,
    "batiment": { "id": 1, "nom": "Bâtiment A" },
    "type_logement": { "id": 2, "nom": "Chambre simple" },
    "logement_propose": { "id": 12, "nomenclature": "A-102" },
    "note_traitement": "Demande acceptée."
  },
  "contrat": {
    "id": 4,
    "statut": "Actif",
    "date_debut": "2025-10-01",
    "date_fin": "2026-06-30",
    "document_signe": null,
    "date_rendez_vous": "2025-09-20T09:00:00+00:00",
    "logement": {
      "id": 12,
      "nomenclature": "A-102",
      "batiment": "Bâtiment A",
      "type": "Chambre simple",
      "prix": 45000
    },
    "administratif": { "nom": "Diallo", "prenom": "Moussa" }
  },
  "activation_progress": {
    "steps": [
      { "label": "Contrat créé", "done": true },
      { "label": "Rendez-vous planifié", "done": true },
      { "label": "Document signé", "done": false },
      { "label": "Paiement initial reçu", "done": false },
      { "label": "Contrat activé", "done": false }
    ],
    "percentage": 40
  },
  "incidents": [
    {
      "id": 2,
      "type": "Panne",
      "description": "Fuite d'eau dans la salle de bain",
      "statut": "Nouveau",
      "created_at": "2025-11-10T14:00:00+00:00"
    }
  ],
  "payment_history": [
    { "mois": "Octobre 2025", "montant": 45000, "statut": "Payé", "date": "2025-10-01" },
    { "mois": "Novembre 2025", "montant": 45000, "statut": "En attente", "date": null },
    { "mois": "Décembre 2025", "montant": 45000, "statut": "À venir", "date": null }
  ]
}
```

---

### 4.3 Profil

#### `GET /student/profile` *(auth requise)*
```json
// Response 200
{
  "id": 3,
  "nom": "Koné",
  "prenom": "Aminata",
  "moyenne_bac": 14.5,
  "profil_complet": true,
  "date_naissance": "2002-05-14",
  "sexe": "Feminin",
  "annee_obtention_bac": 2021,
  "prefecture_origine": "Abidjan",
  "situation_matrimoniale": "Celibataire",
  "photo_path": null,
  "handicaps": []
}
```

#### `PUT /student/profile` *(auth requise)*
```json
// Request (tous les champs sont optionnels — PATCH sémantique)
{
  "nom": "Koné",
  "prenom": "Aminata",
  "date_naissance": "2002-05-14",
  "sexe": "Feminin",                         // "Masculin" | "Feminin"
  "annee_obtention_bac": 2021,
  "moyenne_bac": 14.5,
  "prefecture_origine": "Abidjan",
  "situation_matrimoniale": "Celibataire",   // "Celibataire" | "Marie" | "Divorce" | "Veuf"
  "handicaps": [1, 3]                        // tableau d'IDs (peut être vide [])
}

// Response 200
{
  "message": "Profil mis à jour avec succès.",
  "etudiant": { /* même structure que GET /profile */ }
}

// Response 422 — validation
{ "message": "...", "errors": { "sexe": ["La valeur sélectionnée est invalide."] } }
```

---

### 4.4 Demandes de logement

#### `GET /student/demandes` *(auth requise)*
```json
// Response 200 — tableau
[
  {
    "id": 7,
    "statut": "Validée",           // "En attente" | "En cours" | "Validée" | "Rejetée"
    "date_soumission": "...",
    "date_traitement": "...",
    "priorite": 2,
    "batiment": { "id": 1, "nom": "Bâtiment A" },
    "type_logement": { "id": 2, "nom": "Chambre simple" },
    "logement_propose": { "id": 12, "nomenclature": "A-102" },
    "note_traitement": "Demande acceptée."
  }
]
```

#### `POST /student/demandes` *(auth requise)*
> ⚠️ Nécessite `profil_complet = true`. Une seule demande active autorisée à la fois.

```json
// Request
{
  "batiment_id": 1,
  "type_logement_id": 2
}

// Response 201
{
  "message": "Votre demande de logement a été soumise avec succès.",
  "demande": { /* objet demande */ }
}

// Response 403 — profil incomplet
{ "error": "Vous devez compléter votre profil avant de faire une demande." }

// Response 409 — demande déjà active
{ "error": "Vous avez déjà une demande active." }
```

---

### 4.5 Contrat

#### `GET /student/contrat` *(auth requise)*
```json
// Response 200
{
  "contrat": { /* même structure que dans /dashboard */ },
  "payment_history": [ /* tableau de paiements */ ],
  "activation_progress": { /* étapes */ }
}

// Response 200 — pas de contrat
{ "contrat": null }
```

---

### 4.6 Incidents

#### `POST /student/incidents` *(auth requise)*
```json
// Request
{
  "logement_id": 12,
  "type": "Panne",              // "Panne" | "Dégât" | "Voisinage"
  "description": "La climatisation ne fonctionne plus depuis 2 jours."
}

// Response 201
{
  "message": "Votre signalement a été enregistré.",
  "incident": {
    "id": 5,
    "type": "Panne",
    "description": "...",
    "statut": "Nouveau",
    "created_at": "..."
  }
}
```

---

### 4.7 Données de référence *(publiques)*

#### `GET /student/residences`
```json
// Response 200
[
  {
    "id": 1,
    "nom": "Bâtiment A",
    "adresse": "Campus Nord",
    "photo_url": null,
    "type_logements": [
      { "id": 2, "nom": "Chambre simple", "prix": 45000, "disponibles": 3, "total": 10 }
    ]
  }
]
```

#### `GET /student/reference`
```json
// Response 200 — utilisé pour peupler les listes déroulantes
{
  "batiments": [{ "id": 1, "nom": "Bâtiment A" }],
  "type_logements": [{ "id": 2, "nom": "Chambre simple", "prix": 45000 }],
  "handicaps": [{ "id": 1, "nom": "Mobilité réduite" }, { "id": 2, "nom": "Visuel" }]
}
```

---

## 5. Flux utilisateur (UX)

### 5.1 Arbre de navigation

```
App
├── /login                    ← Toujours accessible
└── /(student) [protégé]
    ├── /                     ← Tab: Dashboard
    ├── /profile              ← Tab: Mon Profil
    ├── /demandes             ← Tab: Ma Demande
    ├── /contrat              ← Tab: Mon Contrat
    └── /incidents/new        ← Modal flottant
```

### 5.2 Règles de navigation & garde-fous

| Condition | Comportement |
|-----------|-------------|
| Token absent | Rediriger vers `/login` immédiatement |
| `profil_complet = false` | Afficher une bannière d'alerte persistante sur le dashboard + bloquer l'accès à `/demandes` |
| `demande.statut = "Validée"` et `contrat = null` | Afficher message "Votre logement est en cours d'attribution" |
| `contrat.statut = "Actif"` | Activer l'onglet **Contrat** et le bouton **Signaler un incident** |
| Erreur 401 de l'API | Effacer le token, rediriger vers `/login` |

### 5.3 Scénarios utilisateur clés

**Scénario A — Première connexion (profil incomplet)**
```
Login → Dashboard (bannière "Complétez votre profil") → Onglet Profil → Formulaire → Sauvegarde → Retour Dashboard (bannière disparaît) → Bouton "Faire une demande" visible
```

**Scénario B — Étudiant avec demande en attente**
```
Dashboard → Carte "Demande en attente" → Onglet Demandes → Détail statut → Icône horloge animée
```

**Scénario C — Étudiant avec contrat actif**
```
Dashboard → Résumé contrat → Onglet Contrat → Échéancier de paiement → Bouton "Signaler un problème" → Modal incident
```

---

## 6. Spécifications écran par écran

### 6.1 Écran Login `/login`

**Éléments UI :**
- Logo IP DEFITECH centré en haut
- Titre "Espace Étudiant"
- Champ email (keyboardType="email-address", autoCapitalize="none")
- Champ mot de passe (secureTextEntry, œil toggle)
- Bouton "Se connecter" (gradient bleu, pleine largeur)
- Lien "Première connexion ? Contactez l'administration"
- Message d'erreur inline sous le bouton

**Logique :**
1. Appeler `POST /student/login`
2. Stocker le token dans `expo-secure-store`
3. Sauvegarder l'objet `user` dans le store Zustand
4. Rediriger vers `/(student)/`

**États :**
- `idle` → formulaire interactif
- `loading` → bouton désactivé + spinner
- `error` → texte rouge sous le formulaire

---

### 6.2 Écran Dashboard `/(student)/index`

**Logique de chargement :**
```typescript
// Appel unique au montage
const { data, isLoading, refetch } = useQuery({
  queryKey: ['dashboard'],
  queryFn: getDashboard,
  staleTime: 2 * 60 * 1000, // 2 minutes
});
```

**Mise en page (ScrollView vertical) :**

```
┌─────────────────────────────────────┐
│  👋 Bonjour Aminata !               │  ← WelcomeHeader
│  Bâtiment A • Chambre A-102         │
└─────────────────────────────────────┘
┌─────────────────────────────────────┐
│  ⚠️  BANNIÈRE si profil_complet=false│  ← Fond orange, CTA vers /profile
└─────────────────────────────────────┘
┌───────────────┐  ┌──────────────────┐
│ 📋 Ma Demande │  │ 📄 Mon Contrat   │  ← StatusCards (2 colonnes)
│ Statut badge  │  │ Statut badge     │
└───────────────┘  └──────────────────┘
┌─────────────────────────────────────┐
│ 🏠 Mon Logement                     │  ← Section si contrat actif
│ A-102 • Bâtiment A • 45 000 FCFA   │
│ Contrat du 01/10 au 30/06          │
└─────────────────────────────────────┘
┌─────────────────────────────────────┐
│ ⚙️ Progression activation           │  ← ProgressBar + étapes (5 steps)
│ ████████░░░░ 40%                    │
└─────────────────────────────────────┘
┌─────────────────────────────────────┐
│ 💳 Derniers paiements               │  ← PaymentTimeline (3 derniers)
│ ✅ Octobre • ⏳ Novembre • ⬜ Déc.  │
└─────────────────────────────────────┘
┌─────────────────────────────────────┐
│ 🔧 Incidents récents                │  ← Liste (max 3)
│ + Bouton "Signaler un problème"     │
└─────────────────────────────────────┘
```

**Pull-to-refresh :** `RefreshControl` doit appeler `refetch()`

---

### 6.3 Écran Profil `/(student)/profile`

**Formulaire en deux sections :**

**Section 1 — Informations personnelles**
| Champ | Type | Valeurs |
|-------|------|---------|
| Nom | TextInput | |
| Prénom | TextInput | |
| Date de naissance | DatePicker | |
| Sexe | Sélecteur boutons | Masculin / Féminin |
| Situation matrimoniale | Picker | Célibataire / Marié / Divorcé / Veuf |
| Préfecture d'origine | TextInput | |

**Section 2 — Parcours scolaire**
| Champ | Type | Valeurs |
|-------|------|---------|
| Année obtention BAC | NumberInput | 1900 – année en cours |
| Moyenne BAC | NumberInput (décimal) | 0 – 20 |
| Handicaps | CheckboxGroup multi-select | Données de `GET /student/reference` |

**Bouton :** "Enregistrer mon profil" → `PUT /student/profile`

**Logique :**
- Pré-remplir les champs depuis `GET /student/profile`
- Après succès : mettre à jour le store Zustand (`profil_complet = true`)
- Afficher un toast de succès

---

### 6.4 Écran Demandes `/(student)/demandes`

**États possibles :**

| État | Affichage |
|------|-----------|
| Profil incomplet | Écran bloqué avec CTA vers /profile |
| Aucune demande | EmptyState + bouton "Faire une demande" |
| Demande active | Carte de statut + timeline |
| Demande rejetée | Carte rouge + possibilité de nouvelle demande |

**Formulaire de nouvelle demande (Modal ou inline) :**
1. Charger les données de `GET /student/reference`
2. Sélecteur Bâtiment (Picker)
3. Sélecteur Type de logement (Picker) — filtré par bâtiment sélectionné
4. Afficher le prix estimé
5. Bouton "Soumettre ma demande" → `POST /student/demandes`

**Carte de statut :**
```
┌─────────────────────────────────────┐
│ Demande #7                 [Validée]│
│ Bâtiment A • Chambre simple         │
│ Soumise le 01/09/2025               │
│ Traitée le 05/09/2025               │
│ Logement proposé : A-102            │
│ "Demande acceptée."                 │
└─────────────────────────────────────┘
```

---

### 6.5 Écran Contrat `/(student)/contrat`

**Sections :**

**1. Détail du contrat**
```
Chambre A-102 — Bâtiment A
Type : Chambre simple
Loyer : 45 000 FCFA / mois
Du 01/10/2025 au 30/06/2026
Gestionnaire : Moussa Diallo
Statut : [Actif]
```

**2. Rendez-vous**
```
📅 Rendez-vous le 20/09/2025 à 09h00
```

**3. Progression de l'activation**
- Barre de progression animée (0 → X%)
- Liste des 5 étapes avec icône ✅ / ⏳

**4. Historique des paiements**
```
┌──────────────┬───────────┬──────────┐
│ Mois         │ Montant   │ Statut   │
├──────────────┼───────────┼──────────┤
│ Octobre 2025 │ 45 000 F  │ ✅ Payé   │
│ Novembre     │ 45 000 F  │ ⏳ Attente│
│ Décembre     │ 45 000 F  │ ⬜ À venir│
└──────────────┴───────────┴──────────┘
```

**5. Bouton d'action**
- Si `contrat.statut = "Actif"` → Afficher **"Signaler un problème"** (navigation vers `incidents/new`)

---

### 6.6 Écran Signalement d'incident `/(student)/incidents/new`

**Formulaire :**
1. Sélecteur de type : `Panne` | `Dégât` | `Voisinage` (chips visuels)
2. Zone de texte description (min 10 caractères, max 500)
3. Info contextuelle : logement_id récupéré depuis le contrat actif
4. Bouton "Envoyer le signalement" → `POST /student/incidents`

**Après soumission :**
- Toast "Signalement enregistré ✅"
- Retour au dashboard avec `refetch()`

---

## 7. Design System (UI)

### 7.1 Palette de couleurs

```typescript
// src/constants/colors.ts
export const Colors = {
  primary:    '#1A56DB',   // Bleu institutionnel
  primaryDark:'#1340A8',
  secondary:  '#0E9F6E',   // Vert succès
  accent:     '#E3A008',   // Jaune attention
  danger:     '#E02424',   // Rouge erreur/rejet
  background: '#F4F6F9',   // Gris très clair
  surface:    '#FFFFFF',
  border:     '#E5E7EB',
  textPrimary:'#111928',
  textSecondary:'#6B7280',
  textLight:  '#9CA3AF',

  // Statuts demande/contrat
  statusWaiting:   '#E3A008',  // "En attente"
  statusProcessing:'#3F83F8',  // "En cours"
  statusValidated: '#0E9F6E',  // "Validée" / "Actif"
  statusRejected:  '#E02424',  // "Rejetée"
  statusNew:       '#6B7280',  // "Nouveau" (incident)
};
```

### 7.2 Typographie

```typescript
// src/constants/theme.ts
export const Typography = {
  fontFamily: {
    regular: 'Inter_400Regular',
    medium:  'Inter_500Medium',
    semibold:'Inter_600SemiBold',
    bold:    'Inter_700Bold',
  },
  fontSize: {
    xs:   11, sm: 13, base: 15, md: 17,
    lg:   19, xl: 22, '2xl': 26, '3xl': 32,
  },
  lineHeight: {
    tight: 1.2, normal: 1.5, relaxed: 1.75,
  },
};
```

### 7.3 Composant Badge (statut)

```typescript
// src/components/ui/Badge.tsx
const statusConfig = {
  'En attente':   { bg: '#FFF3CD', text: '#856404', icon: 'time-outline' },
  'En cours':     { bg: '#CCE5FF', text: '#004085', icon: 'sync-outline' },
  'Validée':      { bg: '#D4EDDA', text: '#155724', icon: 'checkmark-circle-outline' },
  'Rejetée':      { bg: '#F8D7DA', text: '#721C24', icon: 'close-circle-outline' },
  'Actif':        { bg: '#D4EDDA', text: '#155724', icon: 'shield-checkmark-outline' },
  'Nouveau':      { bg: '#E2E8F0', text: '#4A5568', icon: 'alert-circle-outline' },
};
```

### 7.4 Espacements et rayons

```typescript
export const Spacing = {
  xs: 4, sm: 8, md: 12, lg: 16, xl: 24, '2xl': 32,
};
export const Radius = {
  sm: 6, md: 10, lg: 16, xl: 24, full: 9999,
};
export const Shadow = {
  sm: { shadowColor: '#000', shadowOffset: { width: 0, height: 1 },
        shadowOpacity: 0.05, shadowRadius: 2, elevation: 1 },
  md: { shadowColor: '#000', shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.08, shadowRadius: 6, elevation: 3 },
};
```

---

## 8. Gestion d'état et couche API

### 8.1 Client Axios

```typescript
// src/api/client.ts
import axios from 'axios';
import * as SecureStore from 'expo-secure-store';
import { useAuthStore } from '../store/authStore';

export const API_BASE = __DEV__
  ? 'http://10.0.2.2:8000/api'   // Android emulator
  : 'https://votre-domaine.com/api';

const client = axios.create({
  baseURL: API_BASE,
  headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
  timeout: 10000,
});

// Injecter le token automatiquement
client.interceptors.request.use(async (config) => {
  const token = await SecureStore.getItemAsync('authToken');
  if (token) config.headers.Authorization = `Bearer ${token}`;
  return config;
});

// Gérer l'expiration du token (401)
client.interceptors.response.use(
  (response) => response,
  async (error) => {
    if (error.response?.status === 401) {
      await SecureStore.deleteItemAsync('authToken');
      useAuthStore.getState().logout();
    }
    return Promise.reject(error);
  }
);

export default client;
```

### 8.2 Store Auth (Zustand)

```typescript
// src/store/authStore.ts
import { create } from 'zustand';

interface User {
  id: number; email: string; role: string;
  nom?: string; prenom?: string; profil_complet: boolean;
}

interface AuthStore {
  token: string | null;
  user: User | null;
  isAuthenticated: boolean;
  setAuth: (token: string, user: User) => void;
  updateUser: (updates: Partial<User>) => void;
  logout: () => void;
}

export const useAuthStore = create<AuthStore>((set) => ({
  token: null, user: null, isAuthenticated: false,
  setAuth: (token, user) => set({ token, user, isAuthenticated: true }),
  updateUser: (updates) => set((s) => ({ user: s.user ? { ...s.user, ...updates } : null })),
  logout: () => set({ token: null, user: null, isAuthenticated: false }),
}));
```

### 8.3 Hook Dashboard

```typescript
// src/hooks/useDashboard.ts
import { useQuery } from '@tanstack/react-query';
import client from '../api/client';

export const getDashboard = async () => {
  const { data } = await client.get('/student/dashboard');
  return data;
};

export const useDashboard = () => useQuery({
  queryKey: ['student', 'dashboard'],
  queryFn: getDashboard,
  staleTime: 2 * 60 * 1000,
  retry: 2,
});
```

### 8.4 QueryClient (Root Layout)

```typescript
// app/_layout.tsx
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';

const queryClient = new QueryClient({
  defaultOptions: {
    queries: { staleTime: 60_000, gcTime: 5 * 60_000, retry: 1 },
  },
});

export default function RootLayout() {
  return (
    <QueryClientProvider client={queryClient}>
      <Slot />
    </QueryClientProvider>
  );
}
```

---

## 9. Checklist d'implémentation

> Utilise cette liste de contrôle pour suivre ta progression pas à pas.

### Phase 1 — Fondations
- [ ] Initialiser le projet Expo avec la commande de la section 2
- [ ] Installer toutes les dépendances
- [ ] Créer `src/constants/colors.ts` et `src/constants/theme.ts`
- [ ] Créer `src/api/client.ts` avec les interceptors Axios
- [ ] Créer `src/store/authStore.ts` (Zustand)
- [ ] Configurer `app/_layout.tsx` avec QueryClient + AuthProvider
- [ ] Configurer la navigation protégée (redirection si non authentifié)

### Phase 2 — Authentification
- [ ] Implémenter `app/(auth)/login.tsx`
- [ ] Appel `POST /student/login` + stockage token SecureStore
- [ ] Redirection post-login vers `/(student)/`
- [ ] Gestion des erreurs 422 (mauvais identifiants)
- [ ] Persistance du token au redémarrage (lecture SecureStore au démarrage)

### Phase 3 — Dashboard principal
- [ ] Implémenter `src/hooks/useDashboard.ts`
- [ ] Créer `app/(student)/index.tsx` avec ScrollView
- [ ] Composant `WelcomeHeader` (salutation + infos logement)
- [ ] Composant `StatusCard` (demande + contrat, 2 colonnes)
- [ ] Bannière alerte profil incomplet
- [ ] Composant `ProgressBar` (activation)
- [ ] Composant `PaymentTimeline`
- [ ] Composant `IncidentCard`
- [ ] Pull-to-refresh

### Phase 4 — Profil
- [ ] Appel `GET /student/profile` au montage
- [ ] Appel `GET /student/reference` pour les sélecteurs handicaps
- [ ] Formulaire avec tous les champs (section 6.3)
- [ ] Validation locale avant envoi
- [ ] Appel `PUT /student/profile` + mise à jour du store
- [ ] Toast de confirmation

### Phase 5 — Demandes
- [ ] Appel `GET /student/demandes`
- [ ] États : profil incomplet / vide / actif / rejeté
- [ ] Appel `GET /student/reference` pour les Pickers
- [ ] Formulaire de nouvelle demande
- [ ] Appel `POST /student/demandes` + gestion 403/409
- [ ] Affichage de la carte de statut détaillée

### Phase 6 — Contrat
- [ ] Appel `GET /student/contrat`
- [ ] Détail logement + contrat
- [ ] Section rendez-vous
- [ ] Barre de progression animée
- [ ] Tableau des paiements
- [ ] Bouton "Signaler un problème" (conditionnel)

### Phase 7 — Incidents
- [ ] Formulaire avec chips de type
- [ ] Récupérer `logement_id` depuis le contrat actif (store ou query)
- [ ] Appel `POST /student/incidents`
- [ ] Toast de confirmation + refetch dashboard

### Phase 8 — Finitions
- [ ] Gestion globale de l'état de chargement (skeleton screens)
- [ ] Gestion globale des erreurs réseau (écran offline)
- [ ] Déconnexion (`POST /student/logout` + clear SecureStore + reset store)
- [ ] Accessibilité (accessibilityLabel sur tous les boutons)
- [ ] Test sur iOS Simulator et Android Emulator

---

## Notes pour l'IA

> **Ordre d'implémentation recommandé :** Suivre scrupuleusement les phases 1 à 8 dans l'ordre. Ne pas sauter à un écran complexe sans avoir les fondations (client.ts, store, layout) en place.

> **Données manquantes :** Si une donnée retournée par l'API est `null`, toujours afficher un placeholder explicite (ex. `"—"` ou `"Non renseigné"`), jamais un crash.

> **Types TypeScript :** Créer les interfaces correspondant à chaque objet API dans `src/types/index.ts` avant d'implémenter les hooks et les composants.

> **Environnement de test :** Le backend tourne sur `php artisan serve` (port 8000). Sur un émulateur Android, utiliser l'IP `10.0.2.2` à la place de `localhost`.

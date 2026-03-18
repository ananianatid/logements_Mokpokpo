<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Batiment;
use App\Models\DemandeLogement;
use App\Models\Handicap;
use App\Models\IncidentTechnique;
use App\Models\TypeLogement;
use App\Services\HousingActivationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    // ─────────────────────────────────────────────────────────────
    // AUTH
    // ─────────────────────────────────────────────────────────────

    /**
     * POST /api/student/login
     * Authenticate and return a Sanctum token.
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants sont incorrects.'],
            ]);
        }

        // Delete old tokens to avoid accumulation
        $user->tokens()->where('name', 'mobile')->delete();

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'token'    => $token,
            'user'     => [
                'id'    => $user->id,
                'email' => $user->email,
                'role'  => $user->role,
                'nom'   => $user->etudiant?->nom,
                'prenom'=> $user->etudiant?->prenom,
                'profil_complet' => (bool) $user->etudiant?->profil_complet,
            ],
        ]);
    }

    /**
     * POST /api/student/logout
     * Revoke the current token.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnecté avec succès.']);
    }

    // ─────────────────────────────────────────────────────────────
    // DASHBOARD
    // ─────────────────────────────────────────────────────────────

    /**
     * GET /api/student/dashboard
     * Full dashboard data: profile, application, contract, progress, incidents, payments.
     */
    public function dashboard(HousingActivationService $activationService): JsonResponse
    {
        $user     = Auth::user();
        $etudiant = $user->etudiant;

        if (!$etudiant) {
            return response()->json([
                'profil_complet'    => false,
                'demande'           => null,
                'contrat'           => null,
                'activation_progress' => null,
                'incidents'         => [],
                'payment_history'   => [],
            ]);
        }

        $demande = $etudiant->demandeLogements()->latest()->first();

        $contrat = $etudiant->contrats()
            ->with(['logement.batiment', 'logement.type_logement'])
            ->latest()
            ->first();

        $activationProgress = null;
        $incidents          = [];
        $paymentHistory     = [];

        if ($contrat) {
            $activationService->tryActivate($contrat);
            $contrat->refresh();
            $activationProgress = $activationService->getActivationProgress($contrat);

            if ($contrat->logement) {
                $incidents = $contrat->logement->incidents()
                    ->latest()
                    ->limit(3)
                    ->get()
                    ->map(fn($i) => [
                        'id'          => $i->id,
                        'type'        => $i->type,
                        'description' => $i->description,
                        'statut'      => $i->statut,
                        'created_at'  => $i->created_at?->toIso8601String(),
                    ]);
            }

            $paymentHistory = $contrat->getPaiementsStatus();
        }

        return response()->json([
            'profil_complet'    => $etudiant->profil_complet,
            'etudiant'          => $this->formatEtudiant($etudiant),
            'demande'           => $demande ? $this->formatDemande($demande) : null,
            'contrat'           => $contrat ? $this->formatContrat($contrat) : null,
            'activation_progress' => $activationProgress,
            'incidents'         => $incidents,
            'payment_history'   => $paymentHistory,
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // PROFILE
    // ─────────────────────────────────────────────────────────────

    /**
     * GET /api/student/profile
     * Return the authenticated student's full profile.
     */
    public function profile(): JsonResponse
    {
        $user     = Auth::user();
        $etudiant = $user->etudiant;

        if (!$etudiant) {
            return response()->json(['error' => 'Profil étudiant introuvable.'], 404);
        }

        return response()->json($this->formatEtudiant($etudiant, detailed: true));
    }

    /**
     * PUT /api/student/profile
     * Update the authenticated student's profile.
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user     = Auth::user();
        $etudiant = $user->etudiant;

        if (!$etudiant) {
            return response()->json(['error' => 'Profil étudiant introuvable.'], 404);
        }

        $validated = $request->validate([
            'nom'                    => ['sometimes', 'string', 'max:255'],
            'prenom'                 => ['sometimes', 'string', 'max:255'],
            'date_naissance'         => ['sometimes', 'date', 'before:today'],
            'sexe'                   => ['sometimes', 'in:Masculin,Feminin'],
            'annee_obtention_bac'    => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'moyenne_bac'            => ['nullable', 'numeric', 'min:0', 'max:20'],
            'prefecture_origine'     => ['nullable', 'string', 'max:255'],
            'situation_matrimoniale' => ['nullable', 'in:Celibataire,Marie,Divorce,Veuf'],
            'handicaps'              => ['nullable', 'array'],
            'handicaps.*'            => ['exists:handicaps,id'],
        ]);

        $etudiant->update(array_merge(
            $validated,
            ['profil_complet' => true]
        ));

        if (isset($validated['handicaps'])) {
            $etudiant->handicaps()->sync($validated['handicaps']);
        }

        return response()->json([
            'message'  => 'Profil mis à jour avec succès.',
            'etudiant' => $this->formatEtudiant($etudiant->fresh()->load('handicaps'), detailed: true),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // HOUSING APPLICATIONS (DEMANDES)
    // ─────────────────────────────────────────────────────────────

    /**
     * GET /api/student/demandes
     * List all housing applications for the current student.
     */
    public function demandes(): JsonResponse
    {
        $etudiant = Auth::user()->etudiant;

        if (!$etudiant) {
            return response()->json(['error' => 'Profil étudiant introuvable.'], 404);
        }

        $demandes = $etudiant->demandeLogements()
            ->with(['batiment', 'type_logement', 'logement_propose'])
            ->latest()
            ->get()
            ->map(fn($d) => $this->formatDemande($d));

        return response()->json($demandes);
    }

    /**
     * POST /api/student/demandes
     * Submit a new housing application.
     */
    public function storeDemande(Request $request): JsonResponse
    {
        $user     = Auth::user();
        $etudiant = $user->etudiant;

        if (!$etudiant || !$etudiant->profil_complet) {
            return response()->json(['error' => 'Vous devez compléter votre profil avant de faire une demande.'], 403);
        }

        $existing = DemandeLogement::where('etudiant_id', $etudiant->id)
            ->whereIn('statut', ['En attente', 'En cours', 'Validée'])
            ->first();

        if ($existing) {
            return response()->json(['error' => 'Vous avez déjà une demande active.'], 409);
        }

        $validated = $request->validate([
            'batiment_id'      => ['required', 'exists:batiments,id'],
            'type_logement_id' => ['required', 'exists:type_logements,id'],
        ]);

        $demande = DemandeLogement::create([
            'etudiant_id'      => $etudiant->id,
            'batiment_id'      => $validated['batiment_id'],
            'type_logement_id' => $validated['type_logement_id'],
            'date_soumission'  => now(),
            'statut'           => 'En attente',
        ]);

        return response()->json([
            'message' => 'Votre demande de logement a été soumise avec succès.',
            'demande' => $this->formatDemande($demande->load(['batiment', 'type_logement'])),
        ], 201);
    }

    // ─────────────────────────────────────────────────────────────
    // CONTRACT
    // ─────────────────────────────────────────────────────────────

    /**
     * GET /api/student/contrat
     * Return the student's current contract.
     */
    public function contrat(HousingActivationService $activationService): JsonResponse
    {
        $etudiant = Auth::user()->etudiant;

        if (!$etudiant) {
            return response()->json(['error' => 'Profil étudiant introuvable.'], 404);
        }

        $contrat = $etudiant->contrats()
            ->with(['logement.batiment', 'logement.type_logement', 'administratif'])
            ->latest()
            ->first();

        if (!$contrat) {
            return response()->json(['contrat' => null]);
        }

        $activationService->tryActivate($contrat);
        $contrat->refresh();

        return response()->json([
            'contrat'             => $this->formatContrat($contrat),
            'payment_history'     => $contrat->getPaiementsStatus(),
            'activation_progress' => $activationService->getActivationProgress($contrat),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // INCIDENTS
    // ─────────────────────────────────────────────────────────────

    /**
     * POST /api/student/incidents
     * Report a new maintenance incident.
     */
    public function storeIncident(Request $request): JsonResponse
    {
        $etudiant = Auth::user()->etudiant;

        if (!$etudiant) {
            return response()->json(['error' => 'Action non autorisée.'], 403);
        }

        $validated = $request->validate([
            'logement_id' => ['required', 'exists:logements,id'],
            'type'        => ['required', 'in:Panne,Dégât,Voisinage'],
            'description' => ['required', 'string', 'min:10'],
        ]);

        $incident = IncidentTechnique::create([
            'logement_id'    => $validated['logement_id'],
            'signale_par_id' => $etudiant->id,
            'type'           => $validated['type'],
            'description'    => $validated['description'],
            'statut'         => 'Nouveau',
        ]);

        return response()->json([
            'message'  => 'Votre signalement a été enregistré.',
            'incident' => [
                'id'          => $incident->id,
                'type'        => $incident->type,
                'description' => $incident->description,
                'statut'      => $incident->statut,
                'created_at'  => $incident->created_at->toIso8601String(),
            ],
        ], 201);
    }

    // ─────────────────────────────────────────────────────────────
    // REFERENCE DATA
    // ─────────────────────────────────────────────────────────────

    /**
     * GET /api/student/residences
     * Public listing of buildings and room types.
     */
    public function residences(): JsonResponse
    {
        $batiments = Batiment::with(['logements.type_logement'])
            ->get()
            ->map(fn($b) => [
                'id'          => $b->id,
                'nom'         => $b->nom,
                'adresse'     => $b->adresse ?? null,
                'photo_url'   => $b->photo_url ?? null,
                'type_logements' => $b->logements
                    ->groupBy('type_logement_id')
                    ->map(fn($group) => [
                        'id'          => $group->first()->type_logement?->id,
                        'nom'         => $group->first()->type_logement?->nom,
                        'prix'        => $group->first()->type_logement?->prix,
                        'disponibles' => $group->where('statut', 'Disponible')->count(),
                        'total'       => $group->count(),
                    ])
                    ->values(),
            ]);

        return response()->json($batiments);
    }

    /**
     * GET /api/student/reference
     * Return all reference data needed to fill the application form (buildings, room types, handicaps).
     */
    public function referenceData(): JsonResponse
    {
        return response()->json([
            'batiments'      => Batiment::select('id', 'nom')->get(),
            'type_logements' => TypeLogement::select('id', 'nom', 'prix')->get(),
            'handicaps'      => Handicap::select('id', 'nom')->get(),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────────────────────

    private function formatEtudiant($etudiant, bool $detailed = false): array
    {
        $data = [
            'id'             => $etudiant->id,
            'nom'            => $etudiant->nom,
            'prenom'         => $etudiant->prenom,
            'moyenne_bac'    => $etudiant->moyenne_bac,
            'profil_complet' => $etudiant->profil_complet,
        ];

        if ($detailed) {
            $data = array_merge($data, [
                'date_naissance'         => $etudiant->date_naissance?->toDateString(),
                'sexe'                   => $etudiant->sexe,
                'annee_obtention_bac'    => $etudiant->annee_obtention_bac,
                'prefecture_origine'     => $etudiant->prefecture_origine,
                'situation_matrimoniale' => $etudiant->situation_matrimoniale,
                'photo_path'             => $etudiant->photo_path,
                'handicaps'              => $etudiant->handicaps?->map(fn($h) => ['id' => $h->id, 'nom' => $h->nom]),
            ]);
        }

        return $data;
    }

    private function formatDemande($demande): array
    {
        return [
            'id'                 => $demande->id,
            'statut'             => $demande->statut,
            'date_soumission'    => $demande->date_soumission?->toIso8601String(),
            'date_traitement'    => $demande->date_traitement?->toIso8601String(),
            'priorite'           => $demande->priorite,
            'batiment'           => $demande->batiment ? ['id' => $demande->batiment->id, 'nom' => $demande->batiment->nom] : null,
            'type_logement'      => $demande->type_logement ? ['id' => $demande->type_logement->id, 'nom' => $demande->type_logement->nom] : null,
            'logement_propose'   => $demande->logement_propose ? ['id' => $demande->logement_propose->id, 'nomenclature' => $demande->logement_propose->nomenclature] : null,
            'note_traitement'    => $demande->note_traitement,
        ];
    }

    private function formatContrat($contrat): array
    {
        return [
            'id'            => $contrat->id,
            'statut'        => $contrat->statut,
            'date_debut'    => $contrat->date_debut?->toDateString(),
            'date_fin'      => $contrat->date_fin?->toDateString(),
            'document_signe'=> $contrat->document_signe,
            'date_rendez_vous' => $contrat->date_rendez_vous?->toIso8601String(),
            'logement'      => $contrat->logement ? [
                'id'           => $contrat->logement->id,
                'nomenclature' => $contrat->logement->nomenclature,
                'batiment'     => $contrat->logement->batiment?->nom,
                'type'         => $contrat->logement->type_logement?->nom,
                'prix'         => $contrat->logement->type_logement?->prix,
            ] : null,
            'administratif' => $contrat->administratif ? [
                'nom'    => $contrat->administratif->nom,
                'prenom' => $contrat->administratif->prenom,
            ] : null,
        ];
    }
}

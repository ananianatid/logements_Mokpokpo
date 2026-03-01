use App\Models\DemandeLogement;
use App\Models\ContratHabitation;
use App\Services\HousingActivationService;

class DashboardController extends Controller
{
public function index(HousingActivationService $activationService)
{
$user = Auth::user();
$etudiant = $user->etudiant;

if (!$etudiant) {
return view('dashboard', compact('user'));
}

// 1. Get the latest housing application
$demande = $etudiant->demandeLogements()->latest()->first();

// 2. Get the current contract if any
$contrat = $etudiant->contrats()->latest()->first();

$activationProgress = null;
if ($contrat && $contrat->statut !== 'Actif') {
// Try to activate if conditions are met
$activationService->tryActivate($contrat);
// Refresh contract status
$contrat->refresh();
// Get progress for UI
$activationProgress = $activationService->getActivationProgress($contrat);
}

// 3. Get recent incidents if active
$incidents = $contrat && $contrat->logement ? $contrat->logement->incidents()->latest()->take(5)->get() : collect();

return view('dashboard', [
'user' => $user,
'etudiant' => $etudiant,
'demande' => $demande,
'contrat' => $contrat,
'activationProgress' => $activationProgress,
'incidents' => $incidents,
]);
}
}
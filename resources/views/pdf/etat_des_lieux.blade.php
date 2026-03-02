<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            margin: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 10px;
        }

        .university-name {
            font-size: 16pt;
            font-weight: bold;
            color: #1e3a8a;
        }

        .title {
            font-size: 18pt;
            font-weight: bold;
            margin-top: 10px;
            text-transform: uppercase;
        }

        .info-section {
            margin-top: 20px;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }

        .info-grid {
            width: 100%;
        }

        .info-grid td {
            padding: 5px;
        }

        .label {
            font-weight: bold;
            color: #475569;
            width: 35%;
        }

        .section-title {
            font-size: 13pt;
            font-weight: bold;
            border-left: 4px solid #3b82f6;
            padding-left: 10px;
            margin-top: 25px;
            margin-bottom: 10px;
            color: #1e40af;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table.items th,
        table.items td {
            border: 1px solid #cbd5e1;
            padding: 10px;
            text-align: left;
        }

        table.items th {
            background-color: #f1f5f9;
        }

        .footer {
            margin-top: 50px;
        }

        .signature-container {
            width: 100%;
            margin-top: 40px;
        }

        .signature-box {
            width: 45%;
            float: left;
            border: 1px solid #e2e8f0;
            padding: 15px;
            height: 150px;
            border-radius: 8px;
        }

        .signature-box.right {
            float: right;
        }

        .signature-line {
            margin-top: 80px;
            border-top: 1px dashed #94a3b8;
        }

        .clear {
            clear: both;
        }

        .date-footer {
            text-align: right;
            font-style: italic;
            margin-top: 30px;
            font-size: 10pt;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="university-name">Université de Mokpokpo</div>
        <div class="title">État des Lieux - {{ $edl->type }}</div>
        <div>Service des Logements Étudiants</div>
    </div>

    <div class="info-section">
        <table class="info-grid">
            <tr>
                <td class="label">Date du rendez-vous :</td>
                <td><strong>{{ $edl->date_rendez_vous ? $edl->date_rendez_vous->format('d/m/Y H:i') : 'Non planifiée'
                        }}</strong></td>
            </tr>
            <tr>
                <td class="label">Référence Contrat :</td>
                <td>#{{ $edl->contrat->id }}</td>
            </tr>
            <tr>
                <td class="label">Étudiant :</td>
                <td><strong>{{ $edl->contrat->etudiant->nom }} {{ $edl->contrat->etudiant->prenom }}</strong></td>
            </tr>
            <tr>
                <td class="label">Logement :</td>
                <td>{{ $edl->contrat->logement->nomenclature }} (Chambre {{ $edl->contrat->logement->numero_chambre }},
                    {{ $edl->contrat->logement->batiment->nom }})</td>
            </tr>
            <tr>
                <td class="label">Concierge responsable :</td>
                <td>{{ $edl->concierge->nom }} {{ $edl->concierge->prenom }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">ÉVALUATION GÉNÉRALE</div>
    <div style="padding: 15px; border: 1px solid #e2e8f0; min-height: 100px; border-radius: 8px;">
        {{ $edl->remarques_generales ?? 'Aucune remarque spécifique consignée.' }}
    </div>

    <div class="section-title">ÉLÉMENTS DE LA CHAMBRE</div>
    <table class="items">
        <thead>
            <tr>
                <th>Élément</th>
                <th>État</th>
                <th>Observations</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Peinture / Murs</td>
                <td>[ ] Bon [ ] Moyen [ ] Mauvais</td>
                <td></td>
            </tr>
            <tr>
                <td>Plancher / Sol</td>
                <td>[ ] Bon [ ] Moyen [ ] Mauvais</td>
                <td></td>
            </tr>
            <tr>
                <td>Plafond / Éclairage</td>
                <td>[ ] Bon [ ] Moyen [ ] Mauvais</td>
                <td></td>
            </tr>
            <tr>
                <td>Plomberie / Robinetterie</td>
                <td>[ ] Bon [ ] Moyen [ ] Mauvais</td>
                <td></td>
            </tr>
            <tr>
                <td>Mobilier (Bureau, Chaise, Lit)</td>
                <td>[ ] Bon [ ] Moyen [ ] Mauvais</td>
                <td></td>
            </tr>
            <tr>
                <td>Ouvertures (Portes, Fenêtres)</td>
                <td>[ ] Bon [ ] Moyen [ ] Mauvais</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="signature-container">
        <div class="signature-box">
            <strong>Signature Étudiant</strong><br>
            <small>(Lu et approuvé)</small>
            <div class="signature-line"></div>
            @if($edl->signe_etudiant)
            <div style="color: green; font-size: 8pt; margin-top: 5px;">Signé numériquement le {{
                $edl->date_signature_etudiant->format('d/m/Y H:i') }}</div>
            @endif
        </div>
        <div class="signature-box right">
            <strong>Signature Concierge</strong><br>
            <small>(Pour l'Administration)</small>
            <div class="signature-line"></div>
            @if($edl->signe_concierge)
            <div style="color: green; font-size: 8pt; margin-top: 5px;">Signé numériquement le {{
                $edl->date_signature_concierge->format('d/m/Y H:i') }}</div>
            @endif
        </div>
        <div class="clear"></div>
    </div>

    <div class="date-footer">
        Document édité le {{ now()->format('d/m/Y') }} à Lomé.
    </div>
</body>

</html>
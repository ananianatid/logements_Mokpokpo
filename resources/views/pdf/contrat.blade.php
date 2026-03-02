<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12pt;
            line-height: 1.6;
            margin: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 50px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .title {
            font-size: 20pt;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .section {
            margin-top: 30px;
            margin-bottom: 15px;
        }

        .section-title {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 10px;
        }

        .details {
            margin-left: 20px;
        }

        .footer {
            margin-top: 100px;
        }

        .signature-box {
            display: inline-block;
            width: 45%;
            vertical-align: top;
        }

        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #000;
            width: 80%;
        }

        .timestamp {
            font-style: italic;
            font-size: 10pt;
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">CONTRAT D'HABITATION</div>
        <div>Mokpokpo Logements - Université</div>
    </div>

    <div class="section">
        <div class="section-title">ENTRE LES SOUSSIGNÉS :</div>
        <div class="details">
            <strong>L'UNIVERSITÉ</strong>, représentée par Monsieur/Madame <strong>{{ $contrat->administratif->nom }} {{
                $contrat->administratif->prenom }}</strong>, agissant en qualité d'Agent Administratif, ci-après
            dénommée "Le Bailleur", d'une part.
        </div>
        <div style="margin-top: 10px; text-align: center;">ET</div>
        <div class="details" style="margin-top: 10px;">
            Monsieur/Madame <strong>{{ $contrat->etudiant->nom }} {{ $contrat->etudiant->prenom }}</strong>, né(e) le
            <strong>{{ $contrat->etudiant->date_naissance ? $contrat->etudiant->date_naissance->format('d/m/Y') :
                '_______' }}</strong>, demeurant à <strong>{{ $contrat->etudiant->adresse ?? '_______' }}</strong>,
            ci-après dénommé(e) "L'Étudiant(e)", d'autre part.
        </div>
    </div>

    <div class="section">
        <div class="section-title">OBJET DU CONTRAT</div>
        <p>Le présent contrat a pour objet la location d'un logement étudiant situé dans le bâtiment <strong>{{
                $contrat->logement->batiment->nom }}</strong>, chambre numéro <strong>{{
                $contrat->logement->numero_chambre }}</strong>, de type <strong>{{
                $contrat->logement->type_logement->nom }}</strong>.</p>
    </div>

    <div class="section">
        <div class="section-title">DURÉE DU CONTRAT</div>
        <p>Le présent contrat est conclu pour une durée déterminée allant du <strong>{{
                $contrat->date_debut->format('d/m/Y') }}</strong> au <strong>{{ $contrat->date_fin->format('d/m/Y')
                }}</strong>.</p>
    </div>

    <div class="section">
        <div class="section-title">CONDITIONS FINANCIÈRES</div>
        <p>Le montant du loyer mensuel est fixé à <strong>{{ number_format($contrat->logement->type_logement->prix, 0,
                ',', ' ') }} FCFA</strong>. L'étudiant s'engage à payer son loyer avant le 5 de chaque mois.</p>
    </div>

    <div class="section">
        <div class="section-title">OBLIGATIONS DE L'ÉTUDIANT</div>
        <p>L'étudiant s'engage à respecter le règlement intérieur de la résidence, à maintenir les lieux en bon état de
            propreté et de conservation, et à signaler sans délai toute anomalie ou dégradation.</p>
    </div>

    <div class="footer">
        <div class="signature-box">
            <strong>Pour l'Université</strong><br>
            (L'Agent Administratif)
            <div class="signature-line"></div>
        </div>
        <div class="signature-box" style="text-align: right;">
            <strong>L'Étudiant(e)</strong><br>
            (Précédé de la mention "Lu et approuvé")
            <div class="signature-line" style="margin-left: 20%;"></div>
        </div>
    </div>

    <div class="timestamp">
        Fait à ____________, le {{ now()->format('d/m/Y') }}
    </div>
</body>

</html>
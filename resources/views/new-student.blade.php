<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Compte Étudiant — Mokpokpo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 20px;
            padding: 3rem;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5);
        }

        .logo-area {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .logo-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 1rem;
            box-shadow: 0 8px 24px rgba(99, 102, 241, 0.4);
        }

        h1 {
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.4rem;
        }

        .subtitle {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.875rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        label {
            display: block;
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.8rem;
            font-weight: 500;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        input[type="text"] {
            width: 100%;
            padding: 0.875rem 1rem;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            color: #fff;
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.2s ease;
            outline: none;
        }

        input[type="text"]:focus {
            border-color: #6366f1;
            background: rgba(99, 102, 241, 0.1);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        input[type="text"]::placeholder {
            color: rgba(255, 255, 255, 0.25);
        }

        .error-text {
            color: #f87171;
            font-size: 0.8rem;
            margin-top: 0.3rem;
        }

        .btn {
            width: 100%;
            padding: 0.95rem;
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 0.5rem;
            letter-spacing: 0.02em;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.5);
        }

        .btn:active {
            transform: translateY(0);
        }

        /* ---- Result card ---- */
        .result-card {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(5, 150, 105, 0.1));
            border: 1px solid rgba(16, 185, 129, 0.4);
            border-radius: 14px;
            padding: 1.75rem;
            margin-top: 2rem;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .result-title {
            color: #34d399;
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .result-row {
            margin-bottom: 1rem;
        }

        .result-label {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .result-value {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 0.6rem 0.9rem;
            color: #e2e8f0;
            font-size: 0.95rem;
            font-family: 'Courier New', monospace;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            word-break: break-all;
        }

        .copy-btn {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: rgba(255, 255, 255, 0.7);
            border-radius: 6px;
            padding: 0.2rem 0.5rem;
            font-size: 0.7rem;
            cursor: pointer;
            white-space: nowrap;
            flex-shrink: 0;
            transition: all 0.15s ease;
        }

        .copy-btn:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
        }

        .login-link {
            display: block;
            text-align: center;
            margin-top: 1.25rem;
            padding: 0.75rem;
            background: rgba(99, 102, 241, 0.2);
            border: 1px solid rgba(99, 102, 241, 0.4);
            border-radius: 10px;
            color: #a5b4fc;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .login-link:hover {
            background: rgba(99, 102, 241, 0.35);
            color: #fff;
        }

        .warning-text {
            color: rgba(251, 191, 36, 0.9);
            font-size: 0.78rem;
            text-align: center;
            margin-top: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.3rem;
        }
    </style>
</head>

<body>

    <div class="card">

        <div class="logo-area">
            <div class="logo-icon">🎓</div>
            <h1>Nouveau Compte Étudiant</h1>
            <p class="subtitle">Entrez votre nom et prénom pour obtenir vos identifiants</p>
        </div>

        {{-- Display generated credentials --}}
        @if (session('generated_email'))
        <div class="result-card">
            <div class="result-title">
                <span>✅</span>
                <span>Compte créé pour {{ session('student_name') }}</span>
            </div>

            <div class="result-row">
                <div class="result-label">📧 Adresse e-mail étudiant</div>
                <div class="result-value">
                    <span>{{ session('generated_email') }}</span>
                    <button class="copy-btn"
                        onclick="copyText('{{ session('generated_email') }}', this)">Copier</button>
                </div>
            </div>

            <div class="result-row">
                <div class="result-label">🔑 Mot de passe</div>
                <div class="result-value">
                    <span id="pwd-display">{{ session('generated_password') }}</span>
                    <button class="copy-btn"
                        onclick="copyText('{{ session('generated_password') }}', this)">Copier</button>
                </div>
            </div>

            <a href="/" class="login-link">→ Aller à la page de connexion</a>

            <p class="warning-text">⚠️ Notez ces identifiants, ils ne seront pas réaffichés.</p>
        </div>
        @else

        {{-- Form --}}
        <form method="POST" action="{{ route('new-student.store') }}">
            @csrf

            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" placeholder="ex : Koffi" value="{{ old('prenom') }}"
                    autocomplete="given-name" autofocus>
                @error('prenom')
                <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="nom">Nom de famille</label>
                <input type="text" id="nom" name="nom" placeholder="ex : Mensah" value="{{ old('nom') }}"
                    autocomplete="family-name">
                @error('nom')
                <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn">
                Générer mes identifiants →
            </button>
        </form>

        @endif

    </div>

    <script>
        function copyText(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                const original = btn.textContent;
                btn.textContent = '✓ Copié';
                btn.style.color = '#34d399';
                setTimeout(() => {
                    btn.textContent = original;
                    btn.style.color = '';
                }, 1800);
            });
        }
    </script>

</body>

</html>
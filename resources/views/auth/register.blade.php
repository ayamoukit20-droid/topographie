<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription — TopoSmart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            font-family: 'Inter', sans-serif;
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── LEFT PANEL ── */
        .auth-left {
            flex: 1;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 48px;
            overflow: hidden;
            min-height: 100vh;
        }
        .auth-left-bg {
            position: absolute;
            inset: 0;
            background-image: url('/img/survey_field.png');
            background-size: cover;
            background-position: center;
        }
        .auth-left-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to top,
                rgba(3,18,43,0.97) 0%,
                rgba(3,18,43,0.65) 50%,
                rgba(3,18,43,0.3) 100%
            );
        }
        .auth-left-content { position: relative; z-index: 2; }
        .auth-left-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(37,99,235,0.2);
            border: 1px solid rgba(59,130,246,0.4);
            color: #93c5fd;
            padding: 5px 14px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .auth-left-tag-dot {
            width: 6px; height: 6px;
            background: #3b82f6;
            border-radius: 50%;
            animation: blink 2s infinite;
        }
        @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }
        .auth-left h1 {
            font-size: clamp(26px, 3vw, 40px);
            font-weight: 900;
            color: #fff;
            line-height: 1.15;
            letter-spacing: -1px;
            margin-bottom: 16px;
        }
        .auth-left h1 span { color: #fbbf24; }
        .auth-left p {
            font-size: 15px;
            color: rgba(255,255,255,0.55);
            line-height: 1.75;
            max-width: 380px;
            margin-bottom: 32px;
        }
        .step-list { display: flex; flex-direction: column; gap: 14px; }
        .step-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }
        .step-item-num {
            width: 28px; height: 28px;
            background: rgba(37,99,235,0.3);
            border: 1px solid rgba(59,130,246,0.4);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: #60a5fa;
            flex-shrink: 0;
        }
        .step-item-text {
            font-size: 13px;
            color: rgba(255,255,255,0.6);
            padding-top: 4px;
            line-height: 1.5;
        }

        /* ── RIGHT PANEL ── */
        .auth-right {
            width: 540px;
            flex-shrink: 0;
            background: #03122b;
            border-left: 1px solid rgba(255,255,255,0.07);
            overflow-y: auto;
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 48px;
        }
        .auth-form-wrap { width: 100%; padding: 10px 0 40px; }

        /* Logo */
        .auth-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 36px;
        }
        .auth-logo-icon {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, #2563eb, #1a56b0);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
        }
        .auth-logo-icon svg { fill: white; width: 20px; height: 20px; }
        .auth-logo-name { font-size: 18px; font-weight: 800; color: #fff; }
        .auth-logo-sub  { font-size: 11px; color: rgba(255,255,255,0.4); }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: rgba(255,255,255,0.35);
            font-size: 13px;
            text-decoration: none;
            margin-bottom: 32px;
            transition: color 0.2s;
        }
        .back-link:hover { color: #60a5fa; }

        .auth-heading { font-size: 22px; font-weight: 800; color: #fff; margin-bottom: 6px; }
        .auth-subhead { font-size: 14px; color: rgba(255,255,255,0.4); margin-bottom: 28px; }

        /* Form elements */
        .form-group { margin-bottom: 16px; }
        .form-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: rgba(255,255,255,0.45);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 7px;
        }
        .form-input {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1.5px solid rgba(255,255,255,0.09);
            border-radius: 10px;
            padding: 11px 15px;
            color: #fff;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: #2563eb;
            background: rgba(37,99,235,0.1);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
        }
        .form-input::placeholder { color: rgba(255,255,255,0.18); }
        .form-input option { background: #071e3d; color: #fff; }

        .btn-auth {
            width: 100%;
            background: #2563eb;
            border: none;
            border-radius: 10px;
            padding: 13px;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.25s;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-auth:hover {
            background: #3b82f6;
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(37,99,235,0.4);
        }

        .auth-switch {
            text-align: center;
            font-size: 13px;
            color: rgba(255,255,255,0.35);
            margin-top: 22px;
        }
        .auth-switch a { color: #60a5fa; text-decoration: none; font-weight: 600; }
        .auth-switch a:hover { text-decoration: underline; }

        .error-text { color: #f87171; font-size: 12px; margin-top: 4px; }

        .form-section-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.2);
            margin: 20px 0 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.06);
        }

        @media (max-width: 1024px) {
            .auth-left { display: none; }
            .auth-right {
                width: 100%;
                min-height: 100vh;
                padding: 40px 28px;
            }
        }
        @media (max-width: 540px) {
            .auth-right { padding: 28px 20px; }
        }
    </style>
</head>
<body>

    <!-- LEFT VISUAL -->
    <div class="auth-left">
        <div class="auth-left-bg"></div>
        <div class="auth-left-overlay"></div>
        <div class="auth-left-content">
            <div class="auth-left-tag">
                <span class="auth-left-tag-dot"></span>
                Inscrivez-vous gratuitement
            </div>
            <h1>Commencez à gérer<br>vos dossiers<br><span>professionnellement</span></h1>
            <p>Rejoignez la plateforme dédiée aux topographes du Maroc et gérez tous vos dossiers ANCFCC en un seul endroit.</p>
            <div class="step-list">
                <div class="step-item">
                    <div class="step-item-num">1</div>
                    <div class="step-item-text">Créez votre compte en moins de 2 minutes</div>
                </div>
                <div class="step-item">
                    <div class="step-item-num">2</div>
                    <div class="step-item-text">Ajoutez votre premier dossier ANCFCC</div>
                </div>
                <div class="step-item">
                    <div class="step-item-num">3</div>
                    <div class="step-item-text">Gérez documents, calculs et PDF automatiquement</div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT FORM -->
    <div class="auth-right">
        <div class="auth-form-wrap">

            <a href="{{ route('home') }}" class="back-link">
                <i class="bi bi-arrow-left"></i> Retour à l'accueil
            </a>

            <div class="auth-logo">
                <div class="auth-logo-icon">
                    <svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                </div>
                <div>
                    <div class="auth-logo-name">TopoSmart</div>
                    <div class="auth-logo-sub">ANCFCC — Maroc</div>
                </div>
            </div>

            <div class="auth-heading">Créer un compte</div>
            <div class="auth-subhead">Rejoignez la plateforme TopoSmart</div>

            <form method="POST" action="{{ route('register') }}" id="register-form">
                @csrf

                <div class="form-section-label">Informations personnelles</div>

                <div class="form-group">
                    <label class="form-label" for="name">Nom complet</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           class="form-input" placeholder="Ahmed Benali" required autofocus>
                    @error('name') <div class="error-text">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Adresse email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="form-input" placeholder="ahmed@exemple.com" required>
                    @error('email') <div class="error-text">{{ $message }}</div> @enderror
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label" for="telephone">Telephone</label>
                            <input type="text" id="telephone" name="telephone" value="{{ old('telephone') }}"
                                   class="form-input" placeholder="+212 6XX XXX">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-label" for="specialite">Specialite</label>
                            <input type="text" id="specialite" name="specialite" value="{{ old('specialite') }}"
                                   class="form-input" placeholder="Geometre">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="organisation">Organisation / Cabinet</label>
                    <input type="text" id="organisation" name="organisation" value="{{ old('organisation') }}"
                           class="form-input" placeholder="Cabinet de topographie...">
                </div>

                <div class="form-section-label">Securite du compte</div>

                <div class="form-group">
                    <label class="form-label" for="password">Mot de passe</label>
                    <input type="password" id="password" name="password"
                           class="form-input" placeholder="Min. 8 caractères" required>
                    @error('password') <div class="error-text">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirmer le mot de passe</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="form-input" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-auth" id="register-submit-btn">
                    <i class="bi bi-person-check"></i> Créer mon compte
                </button>
            </form>

            <div class="auth-switch">
                Vous avez déjà un compte ?
                <a href="{{ route('login') }}">Se connecter</a>
            </div>
        </div>
    </div>

</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — TopoSmart</title>
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
            background-image: url('/img/hero_topography.png');
            background-size: cover;
            background-position: center;
        }
        .auth-left-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to top,
                rgba(3,18,43,0.97) 0%,
                rgba(3,18,43,0.7) 50%,
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
        @keyframes blink {
            0%, 100% { opacity: 1; } 50% { opacity: 0.3; }
        }
        .auth-left h1 {
            font-size: clamp(28px, 3.5vw, 42px);
            font-weight: 900;
            color: #fff;
            line-height: 1.15;
            letter-spacing: -1px;
            margin-bottom: 16px;
        }
        .auth-left h1 span { color: #60a5fa; }
        .auth-left p {
            font-size: 15px;
            color: rgba(255,255,255,0.55);
            line-height: 1.75;
            max-width: 400px;
            margin-bottom: 36px;
        }
        .auth-features { display: flex; flex-direction: column; gap: 12px; }
        .auth-feat {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            color: rgba(255,255,255,0.65);
        }
        .auth-feat-dot {
            width: 8px; height: 8px;
            background: #2563eb;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* ── RIGHT PANEL ── */
        .auth-right {
            width: 480px;
            flex-shrink: 0;
            background: #03122b;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 48px;
            border-left: 1px solid rgba(255,255,255,0.07);
            overflow-y: auto;
            min-height: 100vh;
        }
        .auth-form-wrap { width: 100%; }

        /* Logo */
        .auth-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
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

        .auth-heading { font-size: 22px; font-weight: 800; color: #fff; margin-bottom: 6px; }
        .auth-subhead { font-size: 14px; color: rgba(255,255,255,0.45); margin-bottom: 32px; }

        /* Form */
        .form-group { margin-bottom: 18px; }
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 8px;
        }
        .form-input {
            width: 100%;
            background: rgba(255,255,255,0.06);
            border: 1.5px solid rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 12px 16px;
            color: #fff;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: #2563eb;
            background: rgba(37,99,235,0.1);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.2);
        }
        .form-input::placeholder { color: rgba(255,255,255,0.2); }

        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }
        .form-row label { margin-bottom: 0; }

        .forgot-link {
            font-size: 12px;
            color: #60a5fa;
            text-decoration: none;
            font-weight: 500;
        }
        .forgot-link:hover { text-decoration: underline; }

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

        .auth-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0;
            color: rgba(255,255,255,0.2);
            font-size: 12px;
        }
        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.08);
        }

        .auth-switch {
            text-align: center;
            font-size: 13px;
            color: rgba(255,255,255,0.4);
        }
        .auth-switch a {
            color: #60a5fa;
            text-decoration: none;
            font-weight: 600;
        }
        .auth-switch a:hover { text-decoration: underline; }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
        }
        .remember-row input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: #2563eb;
            cursor: pointer;
        }
        .remember-row label {
            font-size: 13px;
            color: rgba(255,255,255,0.4);
            cursor: pointer;
            margin: 0;
        }

        .error-text { color: #f87171; font-size: 12px; margin-top: 5px; }

        .alert-success-auth {
            background: rgba(16,185,129,0.1);
            border: 1px solid rgba(16,185,129,0.3);
            color: #34d399;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 16px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: rgba(255,255,255,0.35);
            font-size: 13px;
            text-decoration: none;
            margin-bottom: 36px;
            transition: color 0.2s;
        }
        .back-link:hover { color: #60a5fa; }

        @media (max-width: 1024px) {
            .auth-left { display: none; }
            .auth-right {
                width: 100%;
                min-height: 100vh;
                padding: 40px 28px;
            }
        }
        @media (max-width: 520px) {
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
                Plateforme Nationale
            </div>
            <h1>Gérez vos dossiers<br><span>ANCFCC</span><br>intelligemment</h1>
            <p>Immatriculation, copropriété, morcellement, lotissement — tous vos dossiers topographiques organisés en un seul endroit.</p>
            <div class="auth-features">
                <div class="auth-feat"><span class="auth-feat-dot"></span> Checklist automatique par type de dossier</div>
                <div class="auth-feat"><span class="auth-feat-dot"></span> Outils de calcul topographique intégrés</div>
                <div class="auth-feat"><span class="auth-feat-dot"></span> Carte interactive OpenStreetMap</div>
                <div class="auth-feat"><span class="auth-feat-dot"></span> Génération PDF instantanée</div>
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

            <div class="auth-heading">Connexion</div>
            <div class="auth-subhead">Accédez à votre espace de travail</div>

            @if (session('status'))
                <div class="alert-success-auth">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="login-form">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Adresse email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           class="form-input" placeholder="vous@exemple.com" required autofocus>
                    @error('email')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <label class="form-label" for="password">Mot de passe</label>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">Mot de passe oublié ?</a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password"
                           class="form-input" placeholder="••••••••" required>
                    @error('password')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="remember-row">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Se souvenir de moi</label>
                </div>

                <button type="submit" class="btn-auth" id="login-submit-btn">
                    <i class="bi bi-box-arrow-in-right"></i> Se connecter
                </button>
            </form>

            <div class="auth-divider">ou</div>

            <div class="auth-switch">
                Pas encore de compte ?
                <a href="{{ route('register') }}">Créer un compte gratuit</a>
            </div>
        </div>
    </div>

</body>
</html>

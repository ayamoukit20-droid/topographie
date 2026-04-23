<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TopoSmart') — Plateforme ANCFCC</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy-900:   #03122b;
            --navy-800:   #071e3d;
            --navy-700:   #0c2d5c;
            --navy-600:   #103567;
            --blue-500:   #1a56b0;
            --blue-400:   #2563eb;
            --blue-300:   #3b82f6;
            --accent:     #f59e0b;
            --white:      #ffffff;
            --text-light: rgba(255,255,255,0.9);
            --text-muted: rgba(255,255,255,0.45);
            --border:     rgba(255,255,255,0.08);
            --card-bg:    rgba(255,255,255,0.04);
            --card-hover: rgba(255,255,255,0.07);
            --sidebar-w:  256px;
            /* legacy compat */
            --orange:     #2563eb;
            --orange-dark:#1a56b0;
            --orange-glow:rgba(37,99,235,0.12);
            --navy:       rgba(255,255,255,0.05);
            --navy-mid:   rgba(255,255,255,0.08);
            --navy-light: #071e3d;
            --text-dark:  rgba(255,255,255,0.9);
            --bg-page:    #03122b;
            --bg-card:    rgba(255,255,255,0.05);
            --bg-sidebar: #071e3d;
            --shadow-sm:  0 1px 4px rgba(0,0,0,0.3);
            --shadow-md:  0 6px 24px rgba(0,0,0,0.4);
        }

        * { box-sizing: border-box; }

        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--navy-900); }
        ::-webkit-scrollbar-thumb { background: var(--navy-600); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--blue-400); }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--navy-900);
            color: var(--text-light);
            margin: 0;
            min-height: 100vh;
        }

        /* ── SIDEBAR ── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--navy-800);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            z-index: 200;
            overflow-y: auto;
        }

        .sidebar-logo {
            padding: 22px 20px 18px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .sidebar-logo .logo-mark {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--blue-400), var(--blue-500));
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .sidebar-logo .logo-mark svg { fill: white; width: 18px; height: 18px; }
        .logo-name { font-size: 15px; font-weight: 800; color: var(--white); line-height: 1.2; }
        .logo-sub  { font-size: 10px; color: var(--text-muted); margin-top: 1px; }

        .sidebar-section {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1.8px;
            color: var(--text-muted);
            text-transform: uppercase;
            padding: 20px 20px 8px;
            flex-shrink: 0;
        }

        .sidebar-nav { padding: 8px 10px; flex: 1; }

        .nav-item-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 2px;
        }
        .nav-item-link:hover {
            background: var(--card-hover);
            color: var(--white);
        }
        .nav-item-link.active {
            background: rgba(37,99,235,0.18);
            color: #60a5fa;
            font-weight: 600;
            border-left: 3px solid var(--blue-400);
            padding-left: 9px;
        }
        .nav-item-link i { font-size: 16px; width: 20px; text-align: center; flex-shrink: 0; }

        /* Sidebar footer - user card */
        .sidebar-footer {
            padding: 14px;
            border-top: 1px solid var(--border);
            flex-shrink: 0;
        }
        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 10px;
        }
        .user-avatar {
            width: 34px; height: 34px;
            background: var(--blue-400);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 13px; color: #fff;
            flex-shrink: 0;
        }
        .user-name { font-size: 13px; font-weight: 600; color: var(--white); }
        .user-role { font-size: 10px; color: var(--text-muted); }

        /* ── MAIN CONTENT ── */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── TOPBAR ── */
        .topbar {
            height: 62px;
            background: var(--navy-800);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .topbar-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--white);
        }
        .topbar-actions { display: flex; align-items: center; gap: 10px; }

        /* ── BUTTONS ── */
        .btn-orange {
            background: var(--blue-400);
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }
        .btn-orange:hover {
            background: var(--blue-300);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(37,99,235,0.35);
        }
        .btn-outline-orange {
            background: transparent;
            color: #60a5fa;
            border: 1px solid rgba(59,130,246,0.35);
            padding: 7px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: 'Inter', sans-serif;
        }
        .btn-outline-orange:hover {
            background: rgba(37,99,235,0.12);
            color: #93c5fd;
            border-color: rgba(59,130,246,0.5);
        }

        /* ── PAGE CONTENT ── */
        .page-content { padding: 26px; flex: 1; }

        /* ── CARDS ── */
        .card-topo {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 24px;
            transition: border-color 0.3s;
        }
        .card-topo:hover { border-color: rgba(59,130,246,0.2); }

        /* ── STAT CARDS ── */
        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 20px 22px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            background: var(--blue-400);
        }
        .stat-card:hover { border-color: rgba(59,130,246,0.3); transform: translateY(-2px); }

        .stat-icon {
            width: 44px; height: 44px;
            background: rgba(37,99,235,0.12);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            color: #60a5fa;
            margin-bottom: 14px;
        }
        .stat-value { font-size: 28px; font-weight: 800; color: var(--white); line-height: 1; }
        .stat-label { font-size: 12px; color: var(--text-muted); margin-top: 6px; }

        /* ── TABLES ── */
        .table-topo {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 5px;
        }
        .table-topo thead th {
            background: transparent;
            color: var(--text-muted);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 8px 14px;
            border: none;
        }
        .table-topo tbody tr {
            background: var(--card-bg);
            transition: all 0.2s;
        }
        .table-topo tbody tr:hover { background: var(--card-hover); }
        .table-topo tbody td {
            padding: 13px 14px;
            font-size: 13.5px;
            color: var(--text-light);
            border: none;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }
        .table-topo tbody td:first-child {
            border-left: 1px solid var(--border);
            border-radius: 8px 0 0 8px;
        }
        .table-topo tbody td:last-child {
            border-right: 1px solid var(--border);
            border-radius: 0 8px 8px 0;
        }

        /* ── BADGES ── */
        .badge-topo {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-warning   { background: rgba(245,158,11,0.15);  color: #fbbf24; border: 1px solid rgba(245,158,11,0.25); }
        .badge-success   { background: rgba(34,197,94,0.15);   color: #4ade80; border: 1px solid rgba(34,197,94,0.25); }
        .badge-info      { background: rgba(59,130,246,0.15);  color: #60a5fa; border: 1px solid rgba(59,130,246,0.25); }
        .badge-secondary { background: rgba(148,163,184,0.12); color: #94a3b8; border: 1px solid rgba(148,163,184,0.2); }
        .badge-danger    { background: rgba(239,68,68,0.12);   color: #f87171; border: 1px solid rgba(239,68,68,0.2); }

        /* ── FORMS ── */
        .form-control-topo, .form-select-topo {
            background: rgba(255,255,255,0.06);
            border: 1.5px solid var(--border);
            color: var(--white);
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
            font-family: 'Inter', sans-serif;
        }
        .form-control-topo:focus, .form-select-topo:focus {
            background: rgba(37,99,235,0.08);
            border-color: var(--blue-400);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
            color: var(--white);
            outline: none;
        }
        .form-control-topo::placeholder { color: rgba(255,255,255,0.2); }
        .form-select-topo option { background: #071e3d; color: var(--white); }
        .form-label-topo {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 7px;
            display: block;
        }

        /* ── ALERTS ── */
        .alert-topo {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 14px;
            border: 1px solid;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-success-topo { background: rgba(16,185,129,0.1); border-color: rgba(16,185,129,0.25); color: #34d399; }
        .alert-danger-topo  { background: rgba(239,68,68,0.1);  border-color: rgba(239,68,68,0.25);  color: #f87171; }

        /* ── BREADCRUMB ── */
        .breadcrumb-topo {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 20px;
        }
        .breadcrumb-topo a { color: #60a5fa; text-decoration: none; }
        .breadcrumb-topo a:hover { text-decoration: underline; }
        .breadcrumb-topo .sep { color: rgba(255,255,255,0.15); }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
        }
        @if(request()->is('admin*'))
        body.admin-mode {
            --blue-400: #06b6d4;
            --blue-500: #0891b2;
            --blue-300: #22d3ee;
            --orange-glow: rgba(6,182,212,0.12);
        }
        .admin-mode .main-content {
            background-image: 
                radial-gradient(circle at 2px 2px, rgba(6,182,212,0.05) 1px, transparent 0);
            background-size: 32px 32px;
        }
        .admin-mode .logo-mark {
            box-shadow: 0 0 15px rgba(6,182,212,0.3);
            animation: logo-pulse 3s infinite;
        }
        @keyframes logo-pulse {
            0% { transform: scale(1); box-shadow: 0 0 15px rgba(6,182,212,0.3); }
            50% { transform: scale(1.05); box-shadow: 0 0 25px rgba(6,182,212,0.5); }
            100% { transform: scale(1); box-shadow: 0 0 15px rgba(6,182,212,0.3); }
        }
        .admin-mode .topbar {
            border-bottom: 2px solid rgba(6,182,212,0.2);
        }
        @endif
    </style>

    @stack('styles')
</head>
<body class="{{ request()->is('admin*') ? 'admin-mode' : '' }}">

<!-- ═══════════ SIDEBAR ═══════════ -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-mark">
            <svg viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
        </div>
        <div>
            <div class="logo-name">TopoSmart</div>
            <div class="logo-sub">
                @if(request()->is('admin*'))
                    ADMINISTRATION — ANCFCC
                @else
                    ANCFCC — Maroc
                @endif
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">

        {{-- Tableau de bord — commun --}}
        <div class="sidebar-section">Navigation</div>
        <a href="{{ route('dashboard') }}"
           class="nav-item-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Tableau de bord
        </a>

        @if(auth()->user()->isAdmin())
        {{-- ════ MENU ADMINISTRATEUR ════ --}}
        <div class="sidebar-section" style="margin-top:8px;color:rgba(249,115,22,0.8);border-top:1px solid rgba(255,255,255,0.06);padding-top:18px;">Gestion Globale</div>

        <a href="{{ route('admin.dossiers.index') }}"
           class="nav-item-link {{ request()->routeIs('admin.dossiers.*') ? 'active' : '' }}"
           style="color:{{ request()->routeIs('admin.dossiers.*') ? '' : 'rgba(255,255,255,0.75)' }};">
            <i class="bi bi-folder2-open" style="color:#f97316;"></i> Tous les dossiers
        </a>

        <a href="{{ route('admin.users.index') }}"
           class="nav-item-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
           style="color:{{ request()->routeIs('admin.users.*') ? '' : 'rgba(255,255,255,0.75)' }};">
            <i class="bi bi-people-fill" style="color:#f97316;"></i> Utilisateurs
        </a>

        <div class="sidebar-section" style="color:rgba(249,115,22,0.8);">Outils</div>

        <a href="{{ route('outils.index') }}"
           class="nav-item-link {{ request()->routeIs('outils.*') ? 'active' : '' }}"
           style="color:{{ request()->routeIs('outils.*') ? '' : 'rgba(255,255,255,0.75)' }};">
            <i class="bi bi-calculator" style="color:#f97316;"></i> Outils topographiques
        </a>

        <a href="{{ route('chatbot.index') }}"
           class="nav-item-link {{ request()->routeIs('chatbot.*') ? 'active' : '' }}"
           style="color:{{ request()->routeIs('chatbot.*') ? '' : 'rgba(255,255,255,0.75)' }};">
            <i class="bi bi-chat-dots-fill" style="color:#f97316;"></i> Assistant IA
        </a>

        @else
        {{-- ════ MENU UTILISATEUR (Topographe) ════ --}}
        <a href="{{ route('dossiers.index') }}"
           class="nav-item-link {{ request()->routeIs('dossiers.index') ? 'active' : '' }}">
            <i class="bi bi-folder2-open"></i> Mes dossiers
        </a>

        <a href="{{ route('dossiers.create') }}"
           class="nav-item-link {{ request()->routeIs('dossiers.create') ? 'active' : '' }}">
            <i class="bi bi-folder-plus"></i> Nouveau dossier
        </a>

        <div class="sidebar-section">Outils</div>

        <a href="{{ route('outils.index') }}"
           class="nav-item-link {{ request()->routeIs('outils.*') ? 'active' : '' }}">
            <i class="bi bi-calculator"></i> Outils topographiques
        </a>

        <a href="{{ route('chatbot.index') }}"
           class="nav-item-link {{ request()->routeIs('chatbot.*') ? 'active' : '' }}">
            <i class="bi bi-chat-dots-fill"></i> Assistant IA
        </a>
        @endif

        {{-- Compte — commun --}}
        <div class="sidebar-section">Compte</div>

        <a href="{{ route('profile.edit') }}"
           class="nav-item-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <i class="bi bi-person-circle"></i> Mon profil
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-item-link w-100 border-0 text-start"
                    style="background:none;cursor:pointer;">
                <i class="bi bi-box-arrow-left"></i> Deconnexion
            </button>
        </form>
    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="user-name">
                    {{ auth()->user()->name }}
                    @if(auth()->user()->isAdmin())
                        <span style="font-size:9px;background:rgba(245,158,11,0.2);color:#fbbf24;padding:1px 5px;border-radius:4px;margin-left:4px;border:1px solid rgba(245,158,11,0.3);">ADMIN</span>
                    @endif
                </div>
                <div class="user-role">{{ auth()->user()->specialite ?? 'Topographe' }}</div>
            </div>
        </div>
    </div>
</aside>

<!-- ═══════════ MAIN ═══════════ -->
<div class="main-content">

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="topbar-title">
            @yield('page-title', 'Tableau de bord')
            @if(request()->is('admin*'))
                <span style="font-size:10px;background:rgba(37,99,235,0.15);color:#60a5fa;padding:3px 10px;border-radius:20px;margin-left:12px;border:1px solid rgba(37,99,235,0.3);font-weight:600;letter-spacing:0.5px;">
                    <i class="bi bi-shield-lock-fill" style="margin-right:4px;"></i> MODULE ADMINISTRATION
                </span>
            @endif
        </div>
        <div class="topbar-actions">
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dossiers.index') }}"
                   class="{{ request()->routeIs('admin.dossiers.*') ? 'btn-orange' : 'btn-outline-orange' }}">
                    <i class="bi bi-folder2-open"></i> Tous les dossiers
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="{{ request()->routeIs('admin.users.*') ? 'btn-orange' : 'btn-outline-orange' }}">
                    <i class="bi bi-people-fill"></i> Utilisateurs
                </a>
            @else
                <a href="{{ route('dossiers.create') }}" class="btn-orange">
                    <i class="bi bi-plus-lg"></i> Nouveau dossier
                </a>
                <a href="{{ route('chatbot.index') }}" class="btn-outline-orange">
                    <i class="bi bi-chat-dots"></i> Assistant
                </a>
            @endif
        </div>
    </div>

    <!-- ALERTS -->
    <div class="px-4 pt-3">
        @if(session('success'))
            <div class="alert-topo alert-success-topo mb-3">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert-topo alert-danger-topo mb-3">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert-topo alert-danger-topo mb-3">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- PAGE CONTENT -->
    <div class="page-content">
        @yield('content')
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>

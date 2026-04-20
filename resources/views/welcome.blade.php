<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TopoSmart — Plateforme Intelligente de Gestion des Dossiers Topographiques</title>
    <meta name="description" content="TopoSmart est la plateforme intelligente dédiée aux topographes du Maroc : gestion des dossiers ANCFCC, checklist documents, calculs topographiques, carte interactive et génération PDF.">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --navy-900: #03122b;
            --navy-800: #071e3d;
            --navy-700: #0c2d5c;
            --navy-600: #103567;
            --blue-500: #1a56b0;
            --blue-400: #2563eb;
            --blue-300: #3b82f6;
            --accent:   #f59e0b;
            --accent-light: rgba(245,158,11,0.15);
            --white:    #ffffff;
            --text-light: rgba(255,255,255,0.85);
            --text-muted: rgba(255,255,255,0.5);
            --border-light: rgba(255,255,255,0.1);
            --card-bg: rgba(255,255,255,0.05);
            --card-hover: rgba(255,255,255,0.09);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--navy-900);
            color: var(--white);
            overflow-x: hidden;
        }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--navy-900); }
        ::-webkit-scrollbar-thumb { background: var(--blue-500); border-radius: 3px; }

        /* ════════════════════════════════
           NAVBAR
        ════════════════════════════════ */
        .navbar-topo {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1000;
            padding: 18px 0;
            transition: all 0.4s ease;
        }
        .navbar-topo.scrolled {
            background: rgba(3,18,43,0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 12px 0;
            box-shadow: 0 4px 30px rgba(0,0,0,0.4);
            border-bottom: 1px solid var(--border-light);
        }
        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .nav-logo-mark {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, var(--blue-400), var(--blue-500));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .nav-logo-mark svg { width: 22px; height: 22px; fill: #fff; }
        .nav-logo-name {
            font-size: 18px;
            font-weight: 800;
            color: var(--white);
            letter-spacing: -0.3px;
        }
        .nav-logo-sub {
            font-size: 10px;
            color: var(--text-muted);
            display: block;
            margin-top: 1px;
            letter-spacing: 0.5px;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .nav-links a {
            font-size: 14px;
            font-weight: 500;
            color: var(--text-light);
            text-decoration: none;
            padding: 7px 14px;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .nav-links a:hover {
            color: var(--white);
            background: var(--card-bg);
        }
        .nav-actions { display: flex; align-items: center; gap: 10px; }
        .btn-nav-login {
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            color: var(--white);
            border: 1.5px solid var(--border-light);
            transition: all 0.2s;
        }
        .btn-nav-login:hover {
            background: var(--card-bg);
            border-color: rgba(255,255,255,0.25);
            color: var(--white);
        }
        .btn-nav-cta {
            padding: 9px 22px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            background: var(--blue-400);
            color: #fff;
            transition: all 0.25s;
        }
        .btn-nav-cta:hover {
            background: var(--blue-300);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(37,99,235,0.4);
        }

        /* ════════════════════════════════
           HERO
        ════════════════════════════════ */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            overflow: hidden;
        }
        .hero-bg {
            position: absolute;
            inset: 0;
            background-image: url('/img/hero_topography.png');
            background-size: cover;
            background-position: center;
            filter: brightness(0.35);
            transform: scale(1.05);
            transition: transform 8s ease-out;
        }
        .hero-bg.loaded { transform: scale(1); }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                105deg,
                rgba(3,18,43,0.92) 0%,
                rgba(3,18,43,0.75) 50%,
                rgba(3,18,43,0.4) 100%
            );
        }
        .hero-content {
            position: relative;
            z-index: 2;
            padding-top: 100px;
            padding-bottom: 80px;
        }
        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(37,99,235,0.2);
            border: 1px solid rgba(59,130,246,0.4);
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #93c5fd;
            margin-bottom: 28px;
        }
        .hero-tag-dot {
            width: 6px; height: 6px;
            background: #3b82f6;
            border-radius: 50%;
            animation: blink 2s infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.8); }
        }
        .hero h1 {
            font-size: clamp(38px, 5.5vw, 68px);
            font-weight: 900;
            line-height: 1.08;
            letter-spacing: -2px;
            color: var(--white);
            margin-bottom: 24px;
        }
        .hero h1 .accent-word {
            color: var(--accent);
        }
        .hero-lead {
            font-size: 18px;
            color: rgba(255,255,255,0.65);
            line-height: 1.75;
            max-width: 520px;
            margin-bottom: 44px;
        }
        .hero-buttons {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 64px;
        }
        .btn-hero-primary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 32px;
            background: var(--blue-400);
            color: #fff;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 6px 24px rgba(37,99,235,0.4);
        }
        .btn-hero-primary:hover {
            background: var(--blue-300);
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 12px 36px rgba(37,99,235,0.5);
        }
        .btn-hero-secondary {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 32px;
            background: transparent;
            color: #fff;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            border: 1.5px solid rgba(255,255,255,0.3);
            transition: all 0.3s;
        }
        .btn-hero-secondary:hover {
            background: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.6);
            color: #fff;
            transform: translateY(-3px);
        }
        .hero-stats {
            display: flex;
            gap: 48px;
            flex-wrap: wrap;
            padding-top: 24px;
            border-top: 1px solid var(--border-light);
        }
        .hero-stat-num {
            font-size: 30px;
            font-weight: 800;
            color: var(--white);
        }
        .hero-stat-num span { color: var(--accent); }
        .hero-stat-label {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 4px;
        }
        /* Hero right side - feature cards */
        .hero-cards {
            position: relative;
            z-index: 2;
            padding-top: 110px;
        }
        .hero-feature-card {
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 16px;
            padding: 22px 24px;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            transition: all 0.3s;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .hero-feature-card:hover {
            background: rgba(255,255,255,0.11);
            border-color: rgba(59,130,246,0.4);
            transform: translateX(6px);
        }
        .hfc-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .hfc-icon svg { width: 24px; height: 24px; }
        .hfc-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 3px;
        }
        .hfc-desc {
            font-size: 12px;
            color: var(--text-muted);
        }
        /* floating scroll indicator */
        .scroll-down {
            position: absolute;
            bottom: 36px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            color: var(--text-muted);
            font-size: 11px;
            letter-spacing: 1px;
            text-transform: uppercase;
            animation: scrollBounce 2s infinite;
        }
        @keyframes scrollBounce {
            0%, 100% { transform: translateX(-50%) translateY(0); }
            50% { transform: translateX(-50%) translateY(8px); }
        }
        .scroll-line {
            width: 1px;
            height: 40px;
            background: linear-gradient(to bottom, var(--text-muted), transparent);
        }

        /* ════════════════════════════════
           SECTION GENERAL
        ════════════════════════════════ */
        section { padding: 100px 0; }
        .sec-tag {
            display: inline-block;
            background: rgba(37,99,235,0.15);
            border: 1px solid rgba(59,130,246,0.3);
            color: #93c5fd;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 50px;
            margin-bottom: 16px;
        }
        .sec-title {
            font-size: clamp(28px, 4vw, 44px);
            font-weight: 800;
            color: var(--white);
            line-height: 1.15;
            letter-spacing: -1px;
            margin-bottom: 16px;
        }
        .sec-sub {
            font-size: 17px;
            color: rgba(255,255,255,0.55);
            line-height: 1.75;
            max-width: 540px;
            margin: 0 auto;
        }
        .sec-divider {
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, var(--blue-400), var(--blue-300));
            border-radius: 2px;
            margin: 16px 0 0;
        }

        /* ════════════════════════════════
           ABOUT SECTION
        ════════════════════════════════ */
        .about-section {
            background: var(--navy-800);
        }
        .about-img-wrap {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
        }
        .about-img-wrap img {
            width: 100%;
            height: 420px;
            object-fit: cover;
            display: block;
            border-radius: 20px;
            transition: transform 0.6s ease;
        }
        .about-img-wrap:hover img { transform: scale(1.03); }
        .about-img-badge {
            position: absolute;
            bottom: 24px;
            left: 24px;
            background: rgba(3,18,43,0.85);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border-light);
            border-radius: 14px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .about-img-badge-num {
            font-size: 28px;
            font-weight: 900;
            color: var(--accent);
            line-height: 1;
        }
        .about-img-badge-label {
            font-size: 12px;
            color: var(--text-light);
            max-width: 120px;
            line-height: 1.4;
        }
        .about-points { list-style: none; padding: 0; margin: 28px 0; }
        .about-points li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 18px;
            font-size: 15px;
            color: rgba(255,255,255,0.75);
            line-height: 1.6;
        }
        .about-check {
            width: 24px; height: 24px;
            background: rgba(37,99,235,0.2);
            border: 1px solid rgba(59,130,246,0.4);
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .about-check svg { width: 14px; height: 14px; stroke: #60a5fa; stroke-width: 2.5; fill: none; }

        /* ════════════════════════════════
           FEATURES SECTION
        ════════════════════════════════ */
        .features-section { background: var(--navy-900); }
        .feat-card {
            background: var(--card-bg);
            border: 1px solid var(--border-light);
            border-radius: 18px;
            padding: 32px;
            height: 100%;
            transition: all 0.35s;
            position: relative;
            overflow: hidden;
        }
        .feat-card::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--blue-400), var(--blue-300));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.35s;
        }
        .feat-card:hover {
            background: var(--card-hover);
            border-color: rgba(59,130,246,0.35);
            transform: translateY(-6px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.4);
        }
        .feat-card:hover::after { transform: scaleX(1); }
        .feat-icon {
            width: 58px; height: 58px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 22px;
        }
        .feat-icon svg { width: 28px; height: 28px; }
        .feat-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 10px;
        }
        .feat-desc {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.75;
        }

        /* ════════════════════════════════
           DOSSIERS SECTION
        ════════════════════════════════ */
        .dossiers-section { background: var(--navy-800); }
        .dossier-card {
            position: relative;
            border-radius: 18px;
            overflow: hidden;
            cursor: default;
            transition: all 0.4s;
            border: 1px solid var(--border-light);
            height: 100%;
        }
        .dossier-card:hover { transform: translateY(-8px); box-shadow: 0 24px 60px rgba(0,0,0,0.5); }
        .dossier-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            display: block;
            transition: transform 0.5s;
        }
        .dossier-card:hover .dossier-img { transform: scale(1.06); }
        .dossier-body {
            background: var(--navy-700);
            padding: 22px;
        }
        .dossier-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .badge-green  { background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.3); }
        .badge-yellow { background: rgba(245,158,11,0.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.3); }
        .badge-blue   { background: rgba(59,130,246,0.15); color: #60a5fa; border: 1px solid rgba(59,130,246,0.3); }
        .badge-orange { background: rgba(249,115,22,0.15); color: #fb923c; border: 1px solid rgba(249,115,22,0.3); }
        .badge-red    { background: rgba(239,68,68,0.15);  color: #f87171; border: 1px solid rgba(239,68,68,0.3); }

        .dossier-name {
            font-size: 16px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 8px;
        }
        .dossier-desc {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.65;
        }
        .dossier-arrow {
            position: absolute;
            top: 16px; right: 16px;
            width: 34px; height: 34px;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(6px);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.3s;
        }
        .dossier-card:hover .dossier-arrow { opacity: 1; transform: scale(1); }
        .dossier-arrow svg { width: 16px; height: 16px; stroke: #fff; stroke-width: 2; fill: none; }

        /* ════════════════════════════════
           HOW IT WORKS
        ════════════════════════════════ */
        .how-section { background: var(--navy-900); }
        .steps-container { position: relative; }
        .steps-line {
            position: absolute;
            left: 28px;
            top: 28px;
            bottom: 28px;
            width: 2px;
            background: linear-gradient(to bottom, var(--blue-400), transparent);
        }
        .step-row {
            display: flex;
            gap: 28px;
            align-items: flex-start;
            margin-bottom: 36px;
            animation: none;
        }
        .step-row:last-child { margin-bottom: 0; }
        .step-circle {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, var(--blue-500), var(--blue-400));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            font-weight: 800;
            color: #fff;
            flex-shrink: 0;
            box-shadow: 0 0 0 6px rgba(37,99,235,0.15);
        }
        .step-body {
            background: var(--card-bg);
            border: 1px solid var(--border-light);
            border-radius: 14px;
            padding: 22px 26px;
            flex: 1;
            transition: all 0.3s;
        }
        .step-body:hover {
            background: var(--card-hover);
            border-color: rgba(59,130,246,0.3);
        }
        .step-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 6px;
        }
        .step-desc {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.65;
        }

        /* ════════════════════════════════
           ADVANTAGES SECTION
        ════════════════════════════════ */
        .adv-section { background: var(--navy-800); }
        .adv-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .adv-card {
            background: var(--card-bg);
            border: 1px solid var(--border-light);
            border-radius: 16px;
            padding: 28px;
            transition: all 0.3s;
        }
        .adv-card:hover {
            background: var(--card-hover);
            border-color: rgba(59,130,246,0.3);
            transform: translateY(-4px);
        }
        .adv-icon {
            width: 50px; height: 50px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 16px;
        }
        .adv-icon svg { width: 26px; height: 26px; }
        .adv-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 8px;
        }
        .adv-desc {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.65;
        }

        /* ════════════════════════════════
           MAP CTA SECTION
        ════════════════════════════════ */
        .map-cta {
            position: relative;
            overflow: hidden;
            padding: 100px 0;
            background: var(--navy-700);
        }
        .map-cta-bg {
            position: absolute;
            inset: 0;
            background-image: url('/img/cadastral_map.png');
            background-size: cover;
            background-position: center;
            filter: brightness(0.2);
        }
        .map-cta-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, var(--navy-900) 40%, rgba(3,18,43,0.7) 100%);
        }
        .map-cta-content { position: relative; z-index: 2; }
        .map-cta h2 {
            font-size: clamp(30px, 4vw, 50px);
            font-weight: 900;
            color: var(--white);
            letter-spacing: -1px;
            margin-bottom: 20px;
        }
        .map-cta p {
            font-size: 17px;
            color: rgba(255,255,255,0.6);
            line-height: 1.75;
            max-width: 520px;
            margin-bottom: 40px;
        }
        .btn-cta-main {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 36px;
            background: var(--blue-400);
            color: #fff;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 6px 24px rgba(37,99,235,0.4);
        }
        .btn-cta-main:hover {
            background: var(--blue-300);
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 12px 36px rgba(37,99,235,0.5);
        }
        .btn-cta-ghost {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 16px 36px;
            background: transparent;
            color: #fff;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            border: 1.5px solid rgba(255,255,255,0.25);
            transition: all 0.3s;
        }
        .btn-cta-ghost:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
            border-color: rgba(255,255,255,0.5);
        }

        /* ════════════════════════════════
           CONTACT SECTION
        ════════════════════════════════ */
        .contact-section { background: var(--navy-900); }
        .contact-form-card {
            background: var(--card-bg);
            border: 1px solid var(--border-light);
            border-radius: 20px;
            padding: 40px;
        }
        .form-group-topo { margin-bottom: 20px; }
        .form-label-topo {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: rgba(255,255,255,0.6);
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .form-input-topo {
            width: 100%;
            background: rgba(255,255,255,0.06);
            border: 1.5px solid var(--border-light);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 14px;
            color: var(--white);
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
        }
        .form-input-topo:focus {
            outline: none;
            border-color: var(--blue-400);
            background: rgba(37,99,235,0.08);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
        }
        .form-input-topo::placeholder { color: rgba(255,255,255,0.25); }
        textarea.form-input-topo { resize: vertical; min-height: 130px; }
        .btn-form-submit {
            width: 100%;
            padding: 14px;
            background: var(--blue-400);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-form-submit:hover {
            background: var(--blue-300);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(37,99,235,0.4);
        }
        .contact-info-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px;
            background: var(--card-bg);
            border: 1px solid var(--border-light);
            border-radius: 14px;
            margin-bottom: 16px;
            transition: all 0.3s;
        }
        .contact-info-item:hover {
            background: var(--card-hover);
            border-color: rgba(59,130,246,0.3);
            transform: translateX(6px);
        }
        .ci-icon {
            width: 46px; height: 46px;
            background: rgba(37,99,235,0.15);
            border: 1px solid rgba(59,130,246,0.25);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .ci-icon svg { width: 22px; height: 22px; stroke: #60a5fa; stroke-width: 2; fill: none; }
        .ci-label { font-size: 11px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .ci-value { font-size: 15px; font-weight: 600; color: var(--white); }

        /* ════════════════════════════════
           FOOTER
        ════════════════════════════════ */
        footer {
            background: var(--navy-800);
            border-top: 1px solid var(--border-light);
            padding: 60px 0 28px;
        }
        .footer-logo-name { font-size: 20px; font-weight: 800; color: var(--white); }
        .footer-logo-sub  { font-size: 12px; color: var(--text-muted); margin-top: 4px; }
        .footer-desc { font-size: 14px; color: var(--text-muted); line-height: 1.75; margin-top: 16px; max-width: 280px; }
        .footer-heading { font-size: 12px; font-weight: 700; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 18px; }
        footer a {
            display: block;
            font-size: 14px;
            color: var(--text-muted);
            text-decoration: none;
            margin-bottom: 10px;
            transition: color 0.2s;
        }
        footer a:hover { color: #60a5fa; }
        .footer-bottom {
            border-top: 1px solid var(--border-light);
            padding-top: 28px;
            margin-top: 48px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 13px;
            color: var(--text-muted);
        }
        .footer-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border-light);
            padding: 5px 14px;
            border-radius: 50px;
            font-size: 12px;
        }

        /* ════════════════════════════════
           RESPONSIVE
        ════════════════════════════════ */
        @media (max-width: 991px) {
            .hero-cards { display: none; }
            .adv-grid { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 768px) {
            section { padding: 70px 0; }
            .hero { min-height: auto; padding-bottom: 0; }
            .hero h1 { letter-spacing: -1px; }
            .hero-buttons { flex-direction: column; }
            .btn-hero-primary, .btn-hero-secondary { width: 100%; justify-content: center; }
            .hero-stats { gap: 28px; }
            .adv-grid { grid-template-columns: 1fr; }
            .steps-line { display: none; }
            .nav-links { display: none; }
        }
    </style>
</head>
<body>

<!-- ══════════════════════════════════
     NAVBAR
══════════════════════════════════ -->
<nav class="navbar-topo" id="navbar">
    <div class="container">
        <div class="nav-inner">
            <a href="#" class="nav-logo">
                <div class="nav-logo-mark">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <div>
                    <div class="nav-logo-name">TopoSmart</div>
                    <span class="nav-logo-sub">ANCFCC — Maroc</span>
                </div>
            </a>

            <div class="nav-links">
                <a href="#a-propos">A Propos</a>
                <a href="#fonctionnalites">Fonctionnalites</a>
                <a href="#dossiers">Dossiers</a>
                <a href="#comment-ca-marche">Processus</a>
                <a href="#contact">Contact</a>
            </div>

            <div class="nav-actions">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-nav-cta">
                            <i class="bi bi-grid me-1"></i>Tableau de bord
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-nav-login">Connexion</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-nav-cta">Commencer</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </div>
</nav>

<!-- ══════════════════════════════════
     HERO
══════════════════════════════════ -->
<section class="hero" id="hero">
    <div class="hero-bg" id="heroBg"></div>
    <div class="hero-overlay"></div>

    <div class="container hero-content">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="hero-tag" data-aos="fade-down" data-aos-delay="100">
                    <span class="hero-tag-dot"></span>
                    Plateforme Nationale Topographique
                </div>

                <h1 data-aos="fade-up" data-aos-delay="200">
                    Gestion Intelligente<br>
                    des Dossiers <span class="accent-word">ANCFCC</span>
                </h1>

                <p class="hero-lead" data-aos="fade-up" data-aos-delay="300">
                    Digitalisez, organisez et gérez l'ensemble de vos dossiers topographiques avec une plateforme conçue spécialement pour les professionnels et les débutants du Maroc.
                </p>

                <div class="hero-buttons" data-aos="fade-up" data-aos-delay="400">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-hero-primary" id="hero-register-btn">
                            <i class="bi bi-person-plus"></i> Créer un compte gratuit
                        </a>
                    @endif
                    <a href="#fonctionnalites" class="btn-hero-secondary" id="hero-features-btn">
                        <i class="bi bi-play-circle"></i> Voir les fonctionnalités
                    </a>
                </div>

                <div class="hero-stats" data-aos="fade-up" data-aos-delay="500">
                    <div>
                        <div class="hero-stat-num">5<span>+</span></div>
                        <div class="hero-stat-label">Types de dossiers ANCFCC</div>
                    </div>
                    <div>
                        <div class="hero-stat-num">100<span>%</span></div>
                        <div class="hero-stat-label">Calculs automatisés</div>
                    </div>
                    <div>
                        <div class="hero-stat-num">PDF</div>
                        <div class="hero-stat-label">Génération instantanée</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 hero-cards" data-aos="fade-left" data-aos-delay="300">
                <div class="hero-feature-card">
                    <div class="hfc-icon" style="background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="1.8">
                            <path d="M9 11l3 3L22 4"/>
                            <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                        </svg>
                    </div>
                    <div>
                        <div class="hfc-title">Checklist Automatique</div>
                        <div class="hfc-desc">Documents obligatoires par type de dossier</div>
                    </div>
                </div>
                <div class="hero-feature-card">
                    <div class="hfc-icon" style="background: rgba(245,158,11,0.15); border: 1px solid rgba(245,158,11,0.3);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="1.8">
                            <rect x="2" y="3" width="20" height="14" rx="2"/>
                            <path d="M8 21h8M12 17v4"/>
                        </svg>
                    </div>
                    <div>
                        <div class="hfc-title">Calculs Topographiques</div>
                        <div class="hfc-desc">Surface Gauss, gisement, tantièmes, conversions</div>
                    </div>
                </div>
                <div class="hero-feature-card">
                    <div class="hfc-icon" style="background: rgba(59,130,246,0.15); border: 1px solid rgba(59,130,246,0.3);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="1.8">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="M21 21l-4.35-4.35"/>
                        </svg>
                    </div>
                    <div>
                        <div class="hfc-title">Carte Interactive</div>
                        <div class="hfc-desc">Localisation et visualisation OpenStreetMap</div>
                    </div>
                </div>
                <div class="hero-feature-card">
                    <div class="hfc-icon" style="background: rgba(168,85,247,0.15); border: 1px solid rgba(168,85,247,0.3);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#c084fc" stroke-width="1.8">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                        </svg>
                    </div>
                    <div>
                        <div class="hfc-title">Génération PDF</div>
                        <div class="hfc-desc">PV Bornage, rapports techniques, fiches dossier</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="scroll-down">
        <div class="scroll-line"></div>
        <span>Défiler</span>
    </div>
</section>

<!-- ══════════════════════════════════
     ABOUT / A PROPOS
══════════════════════════════════ -->
<section class="about-section" id="a-propos">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="about-img-wrap">
                    <img src="/img/survey_field.png" alt="Topographe terrain ANCFCC">
                    <div class="about-img-badge">
                        <div class="about-img-badge-num">ANCFCC</div>
                        <div class="about-img-badge-label">Conformité réglementaire garantie</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="sec-tag">A Propos de TopoSmart</div>
                <h2 class="sec-title">La complexité des<br>dossiers, simplifiée</h2>
                <div class="sec-divider"></div>
                <p style="color: rgba(255,255,255,0.6); font-size: 16px; line-height: 1.8; margin: 20px 0;">
                    La gestion des dossiers topographiques au Maroc implique une multitude de documents techniques, juridiques et administratifs. TopoSmart centralise tout cela dans une seule plateforme intelligente.
                </p>
                <ul class="about-points">
                    <li>
                        <div class="about-check">
                            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        Conformité totale avec les exigences de l'ANCFCC Maroc
                    </li>
                    <li>
                        <div class="about-check">
                            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        Assistance pédagogique intégrée pour les topographes débutants
                    </li>
                    <li>
                        <div class="about-check">
                            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        Automatisation des calculs et génération de documents PDF
                    </li>
                    <li>
                        <div class="about-check">
                            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        Sécurité des données et accès multi-utilisateurs
                    </li>
                </ul>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-cta-main" id="about-register-btn">
                        Démarrer gratuitement <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════
     FONCTIONNALITES
══════════════════════════════════ -->
<section class="features-section" id="fonctionnalites">
    <div class="container">
        <div class="text-center mb-60" style="margin-bottom: 60px;" data-aos="fade-up">
            <div class="sec-tag">Fonctionnalites</div>
            <h2 class="sec-title">Tout ce dont vous avez besoin</h2>
            <p class="sec-sub">Un écosystème complet pour gérer, calculer et documenter vos projets topographiques professionnellement.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="feat-card" id="feat-dossiers">
                    <div class="feat-icon" style="background: rgba(37,99,235,0.15); border: 1px solid rgba(59,130,246,0.25);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="1.8">
                            <path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"/>
                        </svg>
                    </div>
                    <div class="feat-title">Gestion des Dossiers</div>
                    <p class="feat-desc">Créez et gérez vos dossiers ANCFCC par type : Immatriculation, MAJ, Copropriété, Morcellement, Lotissement. Chaque dossier est structuré selon les exigences réglementaires.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="feat-card" id="feat-checklist">
                    <div class="feat-icon" style="background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.25);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="1.8">
                            <path d="M9 11l3 3L22 4"/>
                            <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
                        </svg>
                    </div>
                    <div class="feat-title">Checklist Documents</div>
                    <p class="feat-desc">Affichage automatique de la liste des documents obligatoires selon le type de dossier. Le statut passe automatiquement à "validé" dès que tous les documents sont fournis.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="feat-card" id="feat-calculs">
                    <div class="feat-icon" style="background: rgba(245,158,11,0.15); border: 1px solid rgba(245,158,11,0.25);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="1.8">
                            <rect x="4" y="2" width="16" height="20" rx="2"/>
                            <path d="M8 6h8M8 10h8M8 14h4"/>
                        </svg>
                    </div>
                    <div class="feat-title">Outils de Calcul</div>
                    <p class="feat-desc">Surface par formule de Gauss, gisement, distance, conversions (m², ha, are, ca), tantièmes de copropriété, emprise au sol. Tous les calculs sont réalisés en temps réel.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="feat-card" id="feat-carte">
                    <div class="feat-icon" style="background: rgba(168,85,247,0.15); border: 1px solid rgba(168,85,247,0.25);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#c084fc" stroke-width="1.8">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <div class="feat-title">Carte Interactive</div>
                    <p class="feat-desc">Localisez chaque dossier sur une carte OpenStreetMap via Leaflet.js. Visualisez tous vos projets géolocalisés sur une carte globale avec zoom et clustering.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="feat-card" id="feat-pdf">
                    <div class="feat-icon" style="background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.25);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#f87171" stroke-width="1.8">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                        </svg>
                    </div>
                    <div class="feat-title">Génération PDF</div>
                    <p class="feat-desc">Générez automatiquement les PV de bornage, rapports techniques et fiches dossier au format PDF via DomPDF. Téléchargement instantané depuis l'interface.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="feat-card" id="feat-chatbot">
                    <div class="feat-icon" style="background: rgba(20,184,166,0.15); border: 1px solid rgba(20,184,166,0.25);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#2dd4bf" stroke-width="1.8">
                            <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
                        </svg>
                    </div>
                    <div class="feat-title">Assistant Intelligent</div>
                    <p class="feat-desc">Un chatbot intelligent basé sur mots-clés explique les documents nécessaires, guide les débutants et répond aux questions fréquentes sur les procédures ANCFCC.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════
     DOSSIERS TYPES
══════════════════════════════════ -->
<section class="dossiers-section" id="dossiers">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="sec-tag">Types de Dossiers</div>
            <h2 class="sec-title">5 Types de Missions ANCFCC</h2>
            <p class="sec-sub">Chaque type de dossier détermine automatiquement les formulaires, les documents requis et les outils de calcul disponibles.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="dossier-card" id="dossier-immatriculation">
                    <img src="/img/immatriculation.png" alt="Immatriculation Foncière" class="dossier-img">
                    <div class="dossier-arrow">
                        <svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </div>
                    <div class="dossier-body">
                        <span class="dossier-badge badge-green">Immatriculation</span>
                        <div class="dossier-name">Immatriculation Foncière</div>
                        <div class="dossier-desc">Réquisition, PV de bornage, plan de situation, tableau de coordonnées et calcul de contenance avec formule de Gauss.</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="dossier-card" id="dossier-maj">
                    <img src="/img/cadastral_map.png" alt="Mise à Jour" class="dossier-img">
                    <div class="dossier-arrow">
                        <svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </div>
                    <div class="dossier-body">
                        <span class="dossier-badge badge-yellow">Mise a Jour</span>
                        <div class="dossier-name">Mise à Jour (MAJ)</div>
                        <div class="dossier-desc">Titre foncier, autorisation de construction, plan architecte, permis d'habiter et calcul de surface bâtie comparative.</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="dossier-card" id="dossier-copropriete">
                    <img src="/img/copropriete.png" alt="Copropriété" class="dossier-img">
                    <div class="dossier-arrow">
                        <svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </div>
                    <div class="dossier-body">
                        <span class="dossier-badge badge-blue">Copropriete</span>
                        <div class="dossier-name">Copropriété</div>
                        <div class="dossier-desc">Calcul automatisé des tantièmes (T = Surface/Total × 1000), tableau A et B, règlement de copropriété et état descriptif.</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="dossier-card" id="dossier-morcellement">
                    <img src="/img/cadastral_map.png" alt="Morcellement" class="dossier-img" style="height: 180px;">
                    <div class="dossier-arrow">
                        <svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </div>
                    <div class="dossier-body">
                        <span class="dossier-badge badge-orange">Morcellement</span>
                        <div class="dossier-name">Morcellement</div>
                        <div class="dossier-desc">Note de renseignements, autorisation de division, plan de division et recalcul automatique des nouvelles surfaces parcellaires.</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="dossier-card" id="dossier-lotissement">
                    <img src="/img/lotissement.png" alt="Lotissement" class="dossier-img" style="height: 180px;">
                    <div class="dossier-arrow">
                        <svg viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </div>
                    <div class="dossier-body">
                        <span class="dossier-badge badge-red">Lotissement</span>
                        <div class="dossier-name">Lotissement</div>
                        <div class="dossier-desc">Plan de masse, cahier des charges, plan voirie VRD, plan réseaux, PV de réception et calcul de la surface globale des lots.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════
     HOW IT WORKS
══════════════════════════════════ -->
<section class="how-section" id="comment-ca-marche">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5" data-aos="fade-right">
                <div class="sec-tag">Processus de Travail</div>
                <h2 class="sec-title">Comment ça<br>fonctionne ?</h2>
                <div class="sec-divider"></div>
                <p style="color: rgba(255,255,255,0.55); font-size: 16px; line-height: 1.8; margin: 20px 0;">
                    En quelques étapes simples, du premier login jusqu'à la génération du PDF final. Le workflow est conçu pour être intuitif même pour les débutants.
                </p>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-cta-main mt-3" id="how-register-btn">
                        Commencer maintenant <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                @endif
            </div>
            <div class="col-lg-7" data-aos="fade-left">
                <div class="steps-container">
                    <div class="steps-line"></div>
                    <div class="step-row">
                        <div class="step-circle">1</div>
                        <div class="step-body">
                            <div class="step-title">Créer un compte</div>
                            <div class="step-desc">Inscription rapide et sécurisée. Chaque utilisateur dispose de son propre espace de travail privé.</div>
                        </div>
                    </div>
                    <div class="step-row">
                        <div class="step-circle">2</div>
                        <div class="step-body">
                            <div class="step-title">Créer un nouveau dossier</div>
                            <div class="step-desc">Sélectionnez le type de dossier ANCFCC. Le formulaire et la checklist s'adaptent automatiquement.</div>
                        </div>
                    </div>
                    <div class="step-row">
                        <div class="step-circle">3</div>
                        <div class="step-body">
                            <div class="step-title">Ajouter les documents</div>
                            <div class="step-desc">Uploadez les fichiers requis. Le système suit l'avancement et valide automatiquement le dossier.</div>
                        </div>
                    </div>
                    <div class="step-row">
                        <div class="step-circle">4</div>
                        <div class="step-body">
                            <div class="step-title">Utiliser les outils de calcul</div>
                            <div class="step-desc">Effectuez vos calculs topographiques directement dans le navigateur : surface, gisement, tantièmes...</div>
                        </div>
                    </div>
                    <div class="step-row">
                        <div class="step-circle">5</div>
                        <div class="step-body">
                            <div class="step-title">Générer le PDF</div>
                            <div class="step-desc">Exportez vos rapports techniques, PV de bornage et fiches dossier en un clic au format PDF.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════
     ADVANTAGES
══════════════════════════════════ -->
<section class="adv-section">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <div class="adv-grid">
                    <div class="adv-card" id="adv-time">
                        <div class="adv-icon" style="background: rgba(245,158,11,0.15); border: 1px solid rgba(245,158,11,0.25);">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="1.8">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                        <div class="adv-title">Gain de Temps</div>
                        <div class="adv-desc">Automatisation des tâches répétitives : checklists, calculs et génération PDF.</div>
                    </div>
                    <div class="adv-card" id="adv-errors">
                        <div class="adv-icon" style="background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.25);">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="1.8">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                            </svg>
                        </div>
                        <div class="adv-title">Réduction des Erreurs</div>
                        <div class="adv-desc">Validation automatique des documents et vérification des calculs topographiques.</div>
                    </div>
                    <div class="adv-card" id="adv-org">
                        <div class="adv-icon" style="background: rgba(59,130,246,0.15); border: 1px solid rgba(59,130,246,0.25);">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="1.8">
                                <path d="M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z"/>
                            </svg>
                        </div>
                        <div class="adv-title">Organisation Professionnelle</div>
                        <div class="adv-desc">Tous vos projets centralisés, catégorisés et accessibles depuis n'importe quel appareil.</div>
                    </div>
                    <div class="adv-card" id="adv-guide">
                        <div class="adv-icon" style="background: rgba(168,85,247,0.15); border: 1px solid rgba(168,85,247,0.25);">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#c084fc" stroke-width="1.8">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/>
                                <line x1="12" y1="17" x2="12.01" y2="17"/>
                            </svg>
                        </div>
                        <div class="adv-title">Aide aux Débutants</div>
                        <div class="adv-desc">Guide intégré, chatbot et explications détaillées pour accompagner les novices.</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" data-aos="fade-left">
                <div class="sec-tag">Avantages Cles</div>
                <h2 class="sec-title">Pourquoi choisir TopoSmart ?</h2>
                <div class="sec-divider"></div>
                <p style="color: rgba(255,255,255,0.55); font-size: 16px; line-height: 1.8; margin: 24px 0;">
                    Conçue par des experts en topographie et en développement logiciel, TopoSmart répond aux défis concrets du terrain marocain avec des solutions pratiques et modernes.
                </p>
                <div style="display: flex; gap: 40px; flex-wrap: wrap; margin-top: 32px; padding-top: 32px; border-top: 1px solid var(--border-light);">
                    <div>
                        <div style="font-size: 32px; font-weight: 800; color: #60a5fa;">5</div>
                        <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Types de dossiers</div>
                    </div>
                    <div>
                        <div style="font-size: 32px; font-weight: 800; color: #34d399;">6+</div>
                        <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Outils de calcul</div>
                    </div>
                    <div>
                        <div style="font-size: 32px; font-weight: 800; color: #fbbf24;">100%</div>
                        <div style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Conformité ANCFCC</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════
     MAP CTA SECTION
══════════════════════════════════ -->
<div class="map-cta">
    <div class="map-cta-bg"></div>
    <div class="map-cta-overlay"></div>
    <div class="container map-cta-content">
        <div class="row">
            <div class="col-lg-7" data-aos="fade-right">
                <div class="sec-tag">Carte Interactive</div>
                <h2>Visualisez vos projets<br>sur la carte</h2>
                <p>Chaque dossier est géolocalisé sur une carte OpenStreetMap interactive. Cliquez pour sélectionner une position, enregistrez les coordonnées et visualisez l'ensemble de vos projets en un coup d'oeil.</p>
                <div style="display: flex; gap: 14px; flex-wrap: wrap;">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-cta-main" id="map-cta-btn">
                            <i class="bi bi-geo-alt"></i> Commencer la cartographie
                        </a>
                    @endif
                    <a href="{{ route('login') }}" class="btn-cta-ghost" id="map-login-btn">
                        Se connecter
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════
     CONTACT
══════════════════════════════════ -->
<section class="contact-section" id="contact">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <div class="sec-tag">Nous Contacter</div>
            <h2 class="sec-title">Une question ? Contactez-nous</h2>
            <p class="sec-sub">Notre équipe est disponible pour répondre à toutes vos questions sur la plateforme ou les procédures ANCFCC.</p>
        </div>

        <div class="row g-5 justify-content-center">
            <div class="col-lg-5" data-aos="fade-right">
                <div class="contact-info-item" id="ci-email">
                    <div class="ci-icon">
                        <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </div>
                    <div>
                        <div class="ci-label">Email</div>
                        <div class="ci-value">contact@toposmart.ma</div>
                    </div>
                </div>
                <div class="contact-info-item" id="ci-phone">
                    <div class="ci-icon">
                        <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.8 19.79 19.79 0 01.1 1.2 2 2 0 012.11 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.09a16 16 0 006 6l.46-.46a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 14.94v1.98z"/></svg>
                    </div>
                    <div>
                        <div class="ci-label">Telephone</div>
                        <div class="ci-value">+212 6 00 00 00 00</div>
                    </div>
                </div>
                <div class="contact-info-item" id="ci-address">
                    <div class="ci-icon">
                        <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div>
                        <div class="ci-label">Adresse</div>
                        <div class="ci-value">Rabat, Maroc</div>
                    </div>
                </div>

                <!-- Info box -->
                <div style="background: rgba(37,99,235,0.1); border: 1px solid rgba(59,130,246,0.25); border-radius: 14px; padding: 22px; margin-top: 24px;">
                    <div style="font-size: 14px; font-weight: 700; color: #60a5fa; margin-bottom: 8px;">A propos de l'ANCFCC</div>
                    <div style="font-size: 13px; color: rgba(255,255,255,0.55); line-height: 1.7;">L'Agence Nationale de la Conservation Foncière, du Cadastre et de la Cartographie est l'organisme officiel au Maroc chargé de la conservation de la propriété foncière.</div>
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-left">
                <div class="contact-form-card" id="contact-form-card">
                    <h4 style="font-size: 20px; font-weight: 700; color: var(--white); margin-bottom: 28px;">Envoyer un message</h4>
                    <form method="POST" action="#" id="contact-form">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group-topo">
                                    <label class="form-label-topo" for="contact-name">Nom complet</label>
                                    <input type="text" id="contact-name" class="form-input-topo" placeholder="Votre nom" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group-topo">
                                    <label class="form-label-topo" for="contact-email">Adresse email</label>
                                    <input type="email" id="contact-email" class="form-input-topo" placeholder="votre@email.com" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group-topo">
                            <label class="form-label-topo" for="contact-subject">Sujet</label>
                            <input type="text" id="contact-subject" class="form-input-topo" placeholder="Objet de votre message">
                        </div>
                        <div class="form-group-topo">
                            <label class="form-label-topo" for="contact-message">Message</label>
                            <textarea id="contact-message" class="form-input-topo" placeholder="Décrivez votre demande..." required></textarea>
                        </div>
                        <button type="submit" class="btn-form-submit" id="contact-submit-btn">
                            <i class="bi bi-send me-2"></i>Envoyer le message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════
     FOOTER
══════════════════════════════════ -->
<footer>
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    <div class="nav-logo-mark" style="background: linear-gradient(135deg, #2563eb, #1a56b0); width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <svg viewBox="0 0 24 24" fill="white" width="20" height="20">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                        </svg>
                    </div>
                    <div>
                        <div class="footer-logo-name">TopoSmart</div>
                        <div class="footer-logo-sub">Plateforme Topographique ANCFCC</div>
                    </div>
                </div>
                <p class="footer-desc">Plateforme intelligente dédiée aux topographes du Maroc pour la gestion professionnelle des dossiers ANCFCC.</p>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="footer-heading">Navigation</div>
                <a href="#a-propos">A Propos</a>
                <a href="#fonctionnalites">Fonctionnalites</a>
                <a href="#dossiers">Types de Dossiers</a>
                <a href="#comment-ca-marche">Processus</a>
                <a href="#contact">Contact</a>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="footer-heading">Dossiers</div>
                <a href="#">Immatriculation</a>
                <a href="#">Mise a Jour (MAJ)</a>
                <a href="#">Copropriete</a>
                <a href="#">Morcellement</a>
                <a href="#">Lotissement</a>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="footer-heading">Outils</div>
                <a href="#">Calcul Surface</a>
                <a href="#">Gisement</a>
                <a href="#">Tantièmes</a>
                <a href="#">Carte Leaflet</a>
                <a href="#">Génération PDF</a>
            </div>
            <div class="col-lg-2 col-md-4">
                <div class="footer-heading">Compte</div>
                @if (Route::has('login'))
                    <a href="{{ route('login') }}">Connexion</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">Créer un compte</a>
                    @endif
                @endif
                <a href="#contact">Assistance</a>
            </div>
        </div>

        <div class="footer-bottom">
            <div>&copy; {{ date('Y') }} TopoSmart — Tous droits reserves</div>
            <div class="footer-badge" id="footer-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="2" width="14" height="14">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                </svg>
                ANCFCC — Maroc
            </div>
        </div>
    </div>
</footer>

<!-- ══════════════════════════════════
     SCRIPTS
══════════════════════════════════ -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    // ── AOS Init
    AOS.init({ duration: 700, once: true, easing: 'ease-out-cubic', offset: 60 });

    // ── Hero BG Ken Burns
    const heroBg = document.getElementById('heroBg');
    if (heroBg) setTimeout(() => heroBg.classList.add('loaded'), 300);

    // ── Navbar Scroll
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 60);
    });

    // ── Counter animation
    function animateCounter(el, target, suffix = '') {
        let start = 0;
        const duration = 1800;
        const step = (timestamp) => {
            if (!start) start = timestamp;
            const progress = Math.min((timestamp - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.floor(eased * target) + suffix;
            if (progress < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    }

    // ── Smooth anchor scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', e => {
            const target = document.querySelector(anchor.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ── Contact form (prevent default demo)
    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('contact-submit-btn');
            btn.textContent = 'Message envoyé !';
            btn.style.background = '#10b981';
            setTimeout(() => {
                btn.innerHTML = '<i class="bi bi-send me-2"></i>Envoyer le message';
                btn.style.background = '';
                contactForm.reset();
            }, 3000);
        });
    }

    // ── Parallax effect on hero
    window.addEventListener('scroll', () => {
        const scrolled = window.scrollY;
        if (heroBg) {
            heroBg.style.transform = `translateY(${scrolled * 0.3}px) scale(1)`;
        }
    });
</script>

</body>
</html>

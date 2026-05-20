<?php
// includes/header.php - Cabecera HTML común (Bento Edition)
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NavaPark2 🎠</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f4f7fb;
            --surface: #ffffff;
            --border: #dbe4ee;
            --accent: #4f8cff;
            --accent2: #a159ff;
            --blue: #5b8def;
            --green: #45c486;
            --text: #1f2937;
            --muted: #6b7280;
            --radius: 16px;
            --font-h: 'Arial', sans-serif;
            --font-b: 'DM Sans', sans-serif;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-b);
            background: linear-gradient(180deg, #f4f7fb 0%, #eef4ff 100%);
            color: var(--text);
            min-height: 100vh;
        }

        /* ── NAV ── */
        nav {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255, 255, 255, .85);
            box-shadow: 0 2px 10px rgba(0, 0, 0, .05);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            padding: 14px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-logo {
            font-family: var(--font-h);
            font-weight: 800;
            font-size: 20px;
            color: var(--accent);
            text-decoration: none;
            letter-spacing: -.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-greeting {
            font-size: 13px;
            color: var(--muted);
            padding: 0 12px;
            border-right: 1px solid var(--border);
            margin-right: 4px;
        }

        .nav-greeting strong {
            color: var(--accent);
        }

        .nav-link {
            color: var(--muted);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            padding: 6px 14px;
            border-radius: 8px;
            transition: all .2s;
        }

        .nav-link:hover {
            color: var(--text);
            background: var(--surface);
        }

        .nav-link.logout {
            color: var(--accent2);
            border: 1px solid rgba(224, 90, 43, .25);
        }

        .nav-link.logout:hover {
            background: rgba(224, 90, 43, .1);
            color: var(--accent2);
        }

        /* ── LAYOUT ── */
        .container {
            max-width: 1020px;
            margin: 36px auto;
            padding: 0 24px;
        }

        /* ── BENTO CARDS ── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 28px;
            margin-bottom: 20px;
            transition: all .25s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        }

        .card-accent-yellow {
            border-top: 3px solid var(--accent);
        }

        .card-accent-blue {
            border-top: 3px solid var(--blue);
        }

        .card-accent-green {
            border-top: 3px solid var(--green);
        }

        .card-accent-red {
            border-top: 3px solid var(--accent2);
        }

        /* ── BENTO GRID ── */
        .bento-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .bento-grid.cols2 {
            grid-template-columns: 1fr 1fr;
        }

        @media (max-width: 680px) {
            .bento-grid {
                grid-template-columns: 1fr !important;
            }
        }

        /* ── TYPOGRAPHY ── */
        h1 {
            font-family: var(--font-h);
            font-weight: 800;
            font-size: 26px;
            color: var(--text);
            margin-bottom: 16px;
            letter-spacing: -.5px;
        }

        h2 {
            font-family: var(--font-h);
            font-weight: 700;
            font-size: 18px;
            color: var(--text);
            margin-bottom: 14px;
        }

        h3 {
            font-family: var(--font-h);
            font-size: 15px;
            color: var(--muted);
        }

        /* ── TABLE ── */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            font-family: var(--font-h);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--muted);
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }

        td {
            padding: 12px 14px;
            border-bottom: 1px solid rgba(255, 255, 255, .04);
            font-size: 14px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: rgba(255, 255, 255, .02);
        }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            font-family: var(--font-b);
            text-decoration: none;
            transition: all .2s;
            white-space: nowrap;
        }

        .btn-primary {
            background: var(--accent);
            color: #0e0e0f;
        }

        .btn-primary:hover {
            background: #3d7af0;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: var(--border);
            color: var(--text);
        }

        .btn-secondary:hover {
            background: #333337;
        }

        .btn-danger {
            background: rgba(224, 90, 43, .15);
            color: var(--accent2);
            border: 1px solid rgba(224, 90, 43, .25);
        }

        .btn-danger:hover {
            background: rgba(224, 90, 43, .25);
        }

        .btn-sm {
            padding: 5px 12px;
            font-size: 12px;
            border-radius: 8px;
        }

        /* ── ALERTS ── */
        .alert {
            padding: 13px 18px;
            border-radius: 10px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .alert-success {
            background: rgba(46, 204, 113, .12);
            color: #5dda8d;
            border: 1px solid rgba(46, 204, 113, .2);
        }

        .alert-error {
            background: rgba(224, 90, 43, .12);
            color: var(--accent2);
            border: 1px solid rgba(224, 90, 43, .2);
        }

        /* ── FORMS ── */
        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 6px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 11px 14px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 14px;
            color: var(--text);
            font-family: var(--font-b);
            transition: border-color .2s;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(79, 140, 255, .15);
        }

        input::placeholder,
        textarea::placeholder {
            color: var(--muted);
        }

        select option {
            background: var(--surface);
        }

        /* ── PILL / TAG ── */
        .pill {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .pill-yellow {
            background: rgba(240, 165, 0, .15);
            color: var(--accent);
        }

        .pill-blue {
            background: rgba(61, 107, 228, .15);
            color: #7a9ef0;
        }

        .pill-green {
            background: rgba(46, 204, 113, .15);
            color: var(--green);
        }

        /* ── STAT CARD ── */
        .stat-num {
            font-family: var(--font-h);
            font-weight: 800;
            font-size: 42px;
            line-height: 1;
            margin-bottom: 6px;
        }

        .stat-label {
            font-size: 12px;
            color: var(--muted);
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        /* ── EMPTY STATE ── */
        .empty {
            text-align: center;
            padding: 50px 20px;
            color: var(--muted);
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 12px;
        }
    </style>
</head>

<body>
    <nav>
        <a href="../index.php" class="nav-logo">🎠 NavaPark2</a>
        <div class="nav-right">
            <?php if (estaLogueado()): ?>
                <div class="nav-greeting">Hola, <strong><?= htmlspecialchars($_SESSION['nombre']) ?></strong></div>
                <?php if (esAdmin()): ?>
                    <a href="../admin/index.php" class="nav-link">Panel Admin</a>
                <?php else: ?>
                    <a href="../cliente/perfil.php" class="nav-link">Mi perfil</a>
                    <a href="../cliente/viaje.php" class="nav-link">Registrar viaje</a>
                <?php endif; ?>
                <a href="../logout.php" class="nav-link logout">Salir →</a>
            <?php else: ?>
                <a href="../index.php" class="nav-link">Inicio</a>
                <a href="../registro.php" class="nav-link">Registro</a>
            <?php endif; ?>
        </div>
    </nav>
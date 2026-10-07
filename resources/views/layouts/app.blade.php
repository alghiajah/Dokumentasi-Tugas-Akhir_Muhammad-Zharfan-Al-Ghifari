<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inspeksi Inventaris Lab') · Inspeksi Lab</title>
    <style>
        :root {
            color-scheme: light;
            --navy: #18324b;
            --blue: #1769aa;
            --blue-dark: #125487;
            --ink: #1d2939;
            --muted: #667085;
            --line: #e4eaf0;
            --surface: #fff;
            --canvas: #f4f7fa;
            --green: #14804a;
            --red: #b42318;
            font-family: Inter, "Segoe UI", Arial, sans-serif;
        }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--canvas); color: var(--ink); }
        a { color: var(--blue); }
        .topbar { background: var(--navy); color: #fff; }
        .topbar-inner { max-width: 1180px; min-height: 68px; margin: auto; padding: 12px 22px; display: flex; align-items: center; justify-content: space-between; gap: 22px; }
        .brand { color: #fff; text-decoration: none; font-size: 16px; font-weight: 750; letter-spacing: .01em; white-space: nowrap; }
        .brand small { display: block; margin-top: 3px; color: #c4d5e5; font-size: 11px; font-weight: 500; }
        .nav { display: flex; align-items: center; gap: 7px; flex-wrap: wrap; }
        .nav a, .nav button { border: 0; border-radius: 7px; padding: 9px 11px; background: transparent; color: #eef5fb; text-decoration: none; font: inherit; font-size: 13px; cursor: pointer; }
        .nav a:hover, .nav button:hover, .nav a.active { background: #294963; }
        .nav form { margin: 0; }
        .page { width: min(1180px, 100%); margin: 0 auto; padding: 30px 22px 48px; }
        .page-heading { display: flex; align-items: flex-start; justify-content: space-between; gap: 18px; margin-bottom: 22px; }
        h1 { margin: 0; color: var(--navy); font-size: clamp(23px, 3vw, 30px); letter-spacing: -.025em; }
        h2 { margin: 0 0 15px; color: var(--navy); font-size: 18px; }
        h3 { margin: 0 0 8px; color: var(--navy); font-size: 16px; }
        p { line-height: 1.55; }
        .subtitle { margin: 7px 0 0; color: var(--muted); font-size: 14px; }
        .panel, .metric, .form-card { border: 1px solid var(--line); border-radius: 12px; background: var(--surface); box-shadow: 0 2px 7px rgb(16 24 40 / 3%); }
        .panel { margin-bottom: 18px; padding: 21px; }
        .metrics { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-bottom: 18px; }
        .metric { padding: 18px; }
        .metric-label { color: var(--muted); font-size: 13px; }
        .metric-value { margin-top: 8px; color: var(--navy); font-size: 29px; font-weight: 750; }
        .metric-note { margin-top: 5px; color: var(--muted); font-size: 12px; }
        .progress { height: 10px; margin-top: 15px; overflow: hidden; border-radius: 999px; background: #e8eef4; }
        .progress span { display: block; height: 100%; border-radius: inherit; background: var(--green); }
        .button { display: inline-flex; min-height: 39px; align-items: center; justify-content: center; gap: 7px; border: 1px solid var(--blue); border-radius: 7px; padding: 8px 13px; background: var(--blue); color: #fff; text-decoration: none; font: inherit; font-size: 13px; font-weight: 650; cursor: pointer; }
        .button:hover { border-color: var(--blue-dark); background: var(--blue-dark); color: #fff; }
        .button.secondary { border-color: #cbd5e1; background: #fff; color: #344054; }
        .button.secondary:hover { background: #f8fafc; }
        .button.success { border-color: var(--green); background: var(--green); }
        .button.danger { border-color: #d0d5dd; background: #fff; color: var(--red); }
        .actions { display: flex; flex-wrap: wrap; gap: 9px; }
        .filter-form, .form-grid { display: flex; align-items: end; flex-wrap: wrap; gap: 12px; }
        .field { display: grid; gap: 6px; min-width: 0; }
        .field label { color: #344054; font-size: 13px; font-weight: 650; }
        input, select, textarea { width: 100%; min-height: 41px; border: 1px solid #cbd5e1; border-radius: 7px; padding: 9px 11px; background: #fff; color: var(--ink); font: inherit; font-size: 14px; }
        textarea { min-height: 106px; resize: vertical; }
        input:focus, select:focus, textarea:focus { outline: 3px solid rgb(23 105 170 / 14%); border-color: var(--blue); }
        .filter-form .field { min-width: 180px; }
        .form-card { width: min(560px, 100%); margin: 0 auto; padding: 25px; }
        .form-card .field { margin-bottom: 16px; }
        .form-actions { display: flex; flex-wrap: wrap; gap: 9px; margin-top: 20px; }
        .alert { margin-bottom: 17px; border: 1px solid; border-radius: 8px; padding: 12px 15px; font-size: 14px; line-height: 1.5; }
        .alert.success { border-color: #a6f4c5; background: #ecfdf3; color: #067647; }
        .alert.error { border-color: #fecdca; background: #fef3f2; color: #b42318; }
        .alert.info { border-color: #b2ddff; background: #eff8ff; color: #175cd3; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th, td { padding: 12px 11px; border-bottom: 1px solid var(--line); text-align: left; vertical-align: top; }
        th { background: #f8fafc; color: #475467; font-size: 11px; letter-spacing: .045em; text-transform: uppercase; }
        tbody tr:last-child td { border-bottom: 0; }
        .badge { display: inline-flex; border-radius: 999px; padding: 5px 9px; font-size: 11px; font-weight: 700; }
        .badge.lengkap { background: #dcfae6; color: #067647; }
        .badge.rusak { background: #fff1e7; color: #b54708; }
        .badge.hilang { background: #fee4e2; color: #b42318; }
        .muted, .empty { color: var(--muted); }
        .empty { padding: 22px 10px; text-align: center; }
        .section-head { display: flex; align-items: baseline; justify-content: space-between; gap: 10px; margin-bottom: 13px; }
        .section-head h2 { margin: 0; }
        .login-wrap { min-height: calc(100vh - 84px); display: grid; place-items: center; padding-top: 12px; }
        .login-card { width: min(420px, 100%); border: 1px solid var(--line); border-radius: 14px; padding: 30px; background: #fff; box-shadow: 0 12px 35px rgb(16 24 40 / 7%); }
        .login-card .field { margin-top: 18px; }
        .login-card .button { width: 100%; margin-top: 20px; }
        .login-mark { width: 44px; height: 44px; display: grid; place-items: center; margin-bottom: 18px; border-radius: 12px; background: #eaf3fb; color: var(--blue); font-weight: 800; }
        #reader { width: min(100%, 520px); margin: 16px auto 0; overflow: hidden; border: 1px solid var(--line); border-radius: 10px; }
        #reader video { border-radius: 8px; }
        .scan-stage { text-align: center; }
        .scan-stage p { color: var(--muted); font-size: 14px; }
        .qr-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(205px, 1fr)); gap: 15px; }
        .qr-label { display: grid; justify-items: center; gap: 7px; min-height: 245px; border: 1px dashed #98a2b3; border-radius: 10px; padding: 17px; background: #fff; text-align: center; break-inside: avoid; }
        .qr-label h3 { margin: 0; }
        .qr-code { width: 150px; height: 150px; display: grid; place-items: center; }
        .qr-code img, .qr-code canvas { max-width: 150px; }
        .qr-id { margin: 0; font-size: 16px; font-weight: 750; }
        .qr-location { margin: 0; color: var(--muted); font-size: 12px; }
        @media (max-width: 850px) { .topbar-inner { align-items: flex-start; flex-direction: column; gap: 9px; } .metrics { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 540px) { .page { padding: 21px 13px 35px; } .panel { padding: 16px; } .metrics { gap: 9px; } .metric { padding: 13px; } .metric-value { font-size: 24px; } .nav { gap: 2px; } .nav a, .nav button { padding: 8px; font-size: 12px; } .page-heading { flex-direction: column; } }
        @media print { body { background: #fff; } .topbar, .no-print, .alert { display: none !important; } .page { width: 100%; padding: 0; } .qr-grid { grid-template-columns: repeat(3, 1fr); gap: 8px; } .qr-label { min-height: 220px; } }
    </style>
    @stack('head')
</head>
<body>
    @auth
        <header class="topbar">
            <div class="topbar-inner">
                <a class="brand" href="{{ route('dashboard') }}">Inspeksi Inventaris Lab<small>Log pemeriksaan harian berbasis QR Code</small></a>
                <nav class="nav" aria-label="Navigasi utama">
                    <a href="{{ route('dashboard') }}" @if (request()->routeIs('dashboard')) class="active" @endif>Dashboard</a>
                    <a href="{{ route('inspeksi.scan') }}" @if (request()->routeIs('inspeksi.*')) class="active" @endif>Inspeksi QR</a>
                    <a href="{{ route('inventaris.index') }}" @if (request()->routeIs('inventaris.index', 'inventaris.create')) class="active" @endif>Inventaris</a>
                    <a href="{{ route('inventaris.labels') }}" @if (request()->routeIs('inventaris.labels')) class="active" @endif>Label QR</a>
                    <span>{{ auth()->user()->nama_asisten }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Keluar</button>
                    </form>
                </nav>
            </div>
        </header>
    @endauth

    <main class="page">
        @if (session('status'))
            <div class="alert success" role="status">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert error" role="alert">{{ $errors->first() }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>

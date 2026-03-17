<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — Stripe Tester</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:      #0b0c0e;
            --surface: #13151a;
            --border:  #222530;
            --stripe:  #635bff;
            --stripe-h:#7c75ff;
            --green:   #00d4a0;
            --red:     #ff4d6a;
            --text:    #e8eaf0;
            --muted:   #5a5f72;
            --mono:    'Space Mono', monospace;
            --sans:    'Syne', sans-serif;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--sans);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Fondo con grid sutil */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(99,91,255,.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99,91,255,.04) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        /* Punto de luz arriba */
        body::after {
            content: '';
            position: fixed;
            top: -200px;
            left: 50%;
            transform: translateX(-50%);
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(99,91,255,.18) 0%, transparent 70%);
            pointer-events: none;
        }

        .card {
            position: relative;
            z-index: 1;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 400px;
            margin: 1rem;
            animation: slideUp .35s cubic-bezier(.16,1,.3,1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .brand {
            text-align: center;
            margin-bottom: 2rem;
        }
        .brand-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px; height: 48px;
            background: rgba(99,91,255,.15);
            border: 1px solid rgba(99,91,255,.3);
            border-radius: 12px;
            font-size: 1.4rem;
            margin-bottom: .85rem;
        }
        .brand h1 {
            font-size: 1.3rem;
            font-weight: 800;
            letter-spacing: -.03em;
        }
        .brand h1 span { color: var(--stripe); }
        .brand p {
            font-size: .8rem;
            color: var(--muted);
            margin-top: .3rem;
            font-family: var(--mono);
        }

        /* Errores de validación */
        .alert-error {
            background: rgba(255,77,106,.1);
            border: 1px solid rgba(255,77,106,.25);
            border-radius: 8px;
            padding: .7rem 1rem;
            font-size: .8rem;
            color: var(--red);
            margin-bottom: 1.25rem;
            font-family: var(--mono);
            line-height: 1.5;
        }
        .alert-error ul { padding-left: 1rem; }

        .field { margin-bottom: 1.1rem; }
        .field label {
            display: block;
            font-size: .75rem;
            color: var(--muted);
            margin-bottom: .4rem;
            font-family: var(--mono);
            text-transform: uppercase;
            letter-spacing: .08em;
        }
        .field input {
            width: 100%;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-family: var(--mono);
            font-size: .88rem;
            padding: .7rem 1rem;
            outline: none;
            transition: border .15s, box-shadow .15s;
        }
        .field input:focus {
            border-color: var(--stripe);
            box-shadow: 0 0 0 3px rgba(99,91,255,.12);
        }
        .field input.error {
            border-color: var(--red);
        }

        .remember {
            display: flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: 1.5rem;
            font-size: .8rem;
            color: var(--muted);
            cursor: pointer;
            user-select: none;
        }
        .remember input[type="checkbox"] {
            width: 15px; height: 15px;
            accent-color: var(--stripe);
            cursor: pointer;
        }

        .btn {
            width: 100%;
            background: var(--stripe);
            color: #fff;
            border: none;
            border-radius: 9px;
            padding: .75rem;
            font-size: .9rem;
            font-family: var(--sans);
            font-weight: 700;
            cursor: pointer;
            transition: all .15s;
            letter-spacing: .01em;
        }
        .btn:hover {
            background: var(--stripe-h);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(99,91,255,.25);
        }
        .btn:active { transform: translateY(0); }

        .footer-note {
            text-align: center;
            margin-top: 1.5rem;
            font-family: var(--mono);
            font-size: .7rem;
            color: var(--muted);
            padding-top: 1.25rem;
            border-top: 1px solid var(--border);
            line-height: 1.6;
        }
        .footer-note strong { color: var(--stripe); }
    </style>
</head>
<body>

<div class="card">

    <div class="brand">
        <div class="brand-icon">⚡</div>
        <h1><span>Stripe</span> Tester</h1>
        <p>Entorno de desarrollo — sandbox</p>
    </div>

    {{-- Errores de validación --}}
    @if ($errors->any())
        <div class="alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Error de credenciales --}}
    @if (session('error'))
        <div class="alert-error">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <div class="field">
            <label>Email</label>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="usuario@ejemplo.com"
                autocomplete="email"
                autofocus
                class="{{ $errors->has('email') ? 'error' : '' }}"
                required
            >
        </div>

        <div class="field">
            <label>Contraseña</label>
            <input
                type="password"
                name="password"
                placeholder="••••••••"
                autocomplete="current-password"
                class="{{ $errors->has('password') ? 'error' : '' }}"
                required
            >
        </div>

        <label class="remember">
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
            Recordarme
        </label>

        <button type="submit" class="btn">Entrar</button>
    </form>

    <div class="footer-note">
        Vista provisional de desarrollo<br>
        <strong>No usar en producción</strong>
    </div>

</div>

</body>
</html>

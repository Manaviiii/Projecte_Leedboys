<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Tester — Pagos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:       #0b0c0e;
            --surface:  #13151a;
            --border:   #222530;
            --stripe:   #635bff;
            --stripe-h: #7c75ff;
            --green:    #00d4a0;
            --red:      #ff4d6a;
            --yellow:   #f5c542;
            --text:     #e8eaf0;
            --muted:    #5a5f72;
            --mono:     'Space Mono', monospace;
            --sans:     'Syne', sans-serif;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--sans);
            min-height: 100vh;
        }

        /* ── Nav ── */
        nav {
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            display: flex;
            align-items: center;
            gap: 2rem;
            height: 56px;
            position: sticky;
            top: 0;
            background: rgba(11,12,14,.92);
            backdrop-filter: blur(12px);
            z-index: 100;
        }
        .nav-brand {
            font-weight: 800;
            font-size: 1.1rem;
            letter-spacing: -.02em;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .nav-brand span { color: var(--stripe); }
        .nav-links { display: flex; gap: .25rem; }
        .nav-links a {
            color: var(--muted);
            text-decoration: none;
            font-size: .85rem;
            padding: .35rem .75rem;
            border-radius: 6px;
            transition: all .15s;
        }
        .nav-links a:hover, .nav-links a.active {
            color: var(--text);
            background: var(--border);
        }
        .nav-badge {
            margin-left: auto;
            background: rgba(99,91,255,.15);
            color: var(--stripe);
            font-family: var(--mono);
            font-size: .7rem;
            padding: .2rem .6rem;
            border-radius: 20px;
            border: 1px solid rgba(99,91,255,.3);
        }

        /* ── Layout ── */
        .page { max-width: 1100px; margin: 0 auto; padding: 2.5rem 2rem; }

        /* ── Token bar ── */
        .token-bar {
            display: flex;
            align-items: center;
            gap: .75rem;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: .75rem 1rem;
            margin-bottom: 2rem;
        }
        .token-bar label {
            font-size: .78rem;
            color: var(--muted);
            white-space: nowrap;
            font-family: var(--mono);
        }
        .token-bar input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            color: var(--text);
            font-family: var(--mono);
            font-size: .82rem;
        }
        .token-bar button {
            background: var(--stripe);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: .4rem .9rem;
            font-size: .78rem;
            font-family: var(--sans);
            font-weight: 600;
            cursor: pointer;
            transition: background .15s;
        }
        .token-bar button:hover { background: var(--stripe-h); }

        /* ── Cards ── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        .card-head {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
        }
        .method {
            font-family: var(--mono);
            font-size: .7rem;
            font-weight: 700;
            padding: .2rem .55rem;
            border-radius: 5px;
        }
        .method.post { background: rgba(0,212,160,.15); color: var(--green); }
        .method.get  { background: rgba(99,91,255,.15);  color: var(--stripe); }
        .card-head h2 { font-size: .95rem; font-weight: 600; }
        .card-head .url {
            margin-left: auto;
            font-family: var(--mono);
            font-size: .75rem;
            color: var(--muted);
        }
        .card-body { padding: 1.25rem; }

        /* ── Form elements ── */
        .field { margin-bottom: 1rem; }
        .field label {
            display: block;
            font-size: .78rem;
            color: var(--muted);
            margin-bottom: .35rem;
            font-family: var(--mono);
        }
        .field input, .field textarea, .field select {
            width: 100%;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 7px;
            color: var(--text);
            font-family: var(--mono);
            font-size: .82rem;
            padding: .6rem .85rem;
            outline: none;
            transition: border .15s;
        }
        .field input:focus, .field textarea:focus, .field select:focus {
            border-color: var(--stripe);
        }
        .field textarea { resize: vertical; min-height: 90px; }

        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            background: var(--stripe);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: .6rem 1.25rem;
            font-size: .875rem;
            font-family: var(--sans);
            font-weight: 700;
            cursor: pointer;
            transition: all .15s;
        }
        .btn:hover { background: var(--stripe-h); transform: translateY(-1px); }
        .btn:active { transform: translateY(0); }
        .btn.danger { background: rgba(255,77,106,.15); color: var(--red); border: 1px solid rgba(255,77,106,.3); }
        .btn.danger:hover { background: rgba(255,77,106,.25); }
        .btn.loading { opacity: .6; pointer-events: none; }

        /* ── Response box ── */
        .response-box {
            margin-top: 1rem;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
            display: none;
        }
        .response-box.show { display: block; }
        .response-head {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .5rem .85rem;
            border-bottom: 1px solid var(--border);
            font-size: .75rem;
            font-family: var(--mono);
        }
        .status-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
        }
        .status-dot.ok  { background: var(--green); }
        .status-dot.err { background: var(--red); }
        .status-dot.warn { background: var(--yellow); }
        .response-pre {
            padding: .85rem;
            font-family: var(--mono);
            font-size: .78rem;
            line-height: 1.6;
            overflow-x: auto;
            max-height: 320px;
            overflow-y: auto;
            white-space: pre-wrap;
            word-break: break-all;
        }
        .response-pre .key   { color: var(--stripe); }
        .response-pre .str   { color: var(--green); }
        .response-pre .num   { color: var(--yellow); }
        .response-pre .bool  { color: var(--red); }
        .response-pre .null  { color: var(--muted); }

        /* ── Estado badge ── */
        .estado {
            display: inline-block;
            font-size: .72rem;
            font-family: var(--mono);
            padding: .18rem .55rem;
            border-radius: 20px;
        }
        .estado.pendiente  { background: rgba(245,197,66,.12);  color: var(--yellow); }
        .estado.pagado     { background: rgba(0,212,160,.12);   color: var(--green);  }
        .estado.fallido    { background: rgba(255,77,106,.12);  color: var(--red);    }
        .estado.reembolsado { background: rgba(99,91,255,.12); color: var(--stripe);  }

        /* ── Section title ── */
        .section-title {
            font-size: .7rem;
            font-family: var(--mono);
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .12em;
            margin-bottom: 1rem;
            padding-bottom: .5rem;
            border-bottom: 1px solid var(--border);
        }

        /* ── Info tip ── */
        .tip {
            background: rgba(99,91,255,.08);
            border: 1px solid rgba(99,91,255,.2);
            border-radius: 8px;
            padding: .7rem 1rem;
            font-size: .78rem;
            color: var(--muted);
            margin-bottom: 1rem;
            line-height: 1.5;
        }
        .tip strong { color: var(--stripe); }

        @media (max-width: 640px) {
            .row { grid-template-columns: 1fr; }
            nav { gap: 1rem; }
            .nav-links a { padding: .3rem .5rem; }
        }
    </style>
</head>
<body>

<nav>
    <div class="nav-brand">⚡ <span>Stripe</span> Tester</div>
    <div class="nav-links">
        <a href="{{ route('pagos.tester') }}"
           class="{{ request()->routeIs('pagos.tester') ? 'active' : '' }}">Pagos</a>
        <a href="{{ route('pagos.historial-view') }}"
           class="{{ request()->routeIs('pagos.historial-view') ? 'active' : '' }}">Historial</a>
    </div>
    <div class="nav-badge">SANDBOX</div>
</nav>

<div class="page">
    @yield('content')
</div>

<script>
    // ── Persistir token en sessionStorage ──────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        const inp = document.getElementById('token-input');
        if (!inp) return;
        const saved = sessionStorage.getItem('bearer_token');
        if (saved) inp.value = saved;
    });

    function saveToken() {
        const inp = document.getElementById('token-input');
        sessionStorage.setItem('bearer_token', inp.value.trim());
        showToast('Token guardado ✓');
    }

    function getToken() {
        return sessionStorage.getItem('bearer_token') || '';
    }

    // ── Toast ───────────────────────────────────────────────────────────────
    function showToast(msg) {
        const t = document.createElement('div');
        t.textContent = msg;
        Object.assign(t.style, {
            position:'fixed', bottom:'1.5rem', right:'1.5rem',
            background:'#222530', color:'#e8eaf0',
            padding:'.6rem 1.1rem', borderRadius:'8px',
            fontSize:'.82rem', fontFamily:"'Space Mono',monospace",
            border:'1px solid #635bff', zIndex:'9999',
            animation:'fadeIn .2s ease'
        });
        document.body.appendChild(t);
        setTimeout(() => t.remove(), 2200);
    }

    // ── Render JSON coloreado ───────────────────────────────────────────────
    function renderJSON(obj) {
        return JSON.stringify(obj, null, 2)
            .replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, (m) => {
                let cls = 'num';
                if (/^"/.test(m)) { cls = /:$/.test(m) ? 'key' : 'str'; }
                else if (/true|false/.test(m)) { cls = 'bool'; }
                else if (/null/.test(m)) { cls = 'null'; }
                return `<span class="${cls}">${m}</span>`;
            });
    }

    // ── Mostrar respuesta ───────────────────────────────────────────────────
    function showResponse(boxId, status, data) {
        const box = document.getElementById(boxId);
        const head = box.querySelector('.response-head');
        const pre  = box.querySelector('.response-pre');
        const dot  = head.querySelector('.status-dot');
        const statusTxt = head.querySelector('.status-txt');

        box.classList.add('show');

        const ok = status >= 200 && status < 300;
        dot.className = 'status-dot ' + (ok ? 'ok' : (status >= 400 ? 'err' : 'warn'));
        statusTxt.textContent = `HTTP ${status}`;
        pre.innerHTML = renderJSON(data);
    }

    // ── Fetch helper ───────────────────────────────────────────────────────
    async function apiCall(method, url, body, boxId, btnId) {
        const btn = document.getElementById(btnId);
        btn.classList.add('loading');
        btn.textContent = 'Enviando…';

        const opts = {
            method,
            headers: {
                'Authorization': 'Bearer ' + getToken(),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        };
        if (body) opts.body = JSON.stringify(body);

        try {
            const res  = await fetch(url, opts);
            const data = await res.json();

            // Guardar pago_id automáticamente si viene en la respuesta
            if (data.pago_id) {
                sessionStorage.setItem('pago_id', data.pago_id);
                sessionStorage.setItem('client_secret', data.clientSecret || '');
                showToast(`pago_id ${data.pago_id} guardado ✓`);
                // rellenar inputs de pago_id si los hay
                document.querySelectorAll('.auto-pago-id').forEach(el => {
                    el.value = data.pago_id;
                });
            }

            showResponse(boxId, res.status, data);
        } catch (e) {
            showResponse(boxId, 0, { error: e.message });
        } finally {
            btn.classList.remove('loading');
            btn.textContent = btn.dataset.label;
        }
    }
</script>

@yield('scripts')
</body>
</html>

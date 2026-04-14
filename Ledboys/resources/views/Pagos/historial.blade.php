@extends('pagos.layout')

@section('content')

{{-- ── Token Bearer ─────────────────────────────────────────────── --}}
<div class="token-bar">
    <label>BEARER TOKEN</label>
    <input id="token-input" type="text" placeholder="Pega tu token de Sanctum aquí…" />
    <button onclick="saveToken()">Guardar</button>
</div>

<p class="section-title">// historial de pagos</p>

{{-- ── Controles ────────────────────────────────────────────────── --}}
<div class="card" style="margin-bottom:1.5rem">
    <div class="card-head">
        <span class="method get">GET</span>
        <h2>Historial del usuario autenticado</h2>
        <span class="url">/api/pagos</span>
    </div>
    <div class="card-body">
        <div style="display:flex; align-items:flex-end; gap:1rem; flex-wrap:wrap;">
            <div class="field" style="margin:0; flex:0 0 160px;">
                <label>Resultados por página</label>
                <select id="per-page">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
            <div class="field" style="margin:0; flex:0 0 120px;">
                <label>Página</label>
                <input type="number" id="page" value="1" min="1">
            </div>
            <button id="btn-hist" data-label="Cargar Historial" class="btn" onclick="cargarHistorial()">
                ▶ Cargar Historial
            </button>
        </div>
    </div>
</div>

{{-- ── Tabla de resultados ──────────────────────────────────────── --}}
<div id="historial-container" style="display:none">

    {{-- Paginación info --}}
    <div id="pagination-info" style="
        font-family: var(--mono);
        font-size: .75rem;
        color: var(--muted);
        margin-bottom: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    ">
        <span id="pag-text"></span>
        <div style="display:flex; gap:.5rem;">
            <button class="btn" style="padding:.35rem .75rem; font-size:.75rem;" onclick="irPagina(-1)">← Anterior</button>
            <button class="btn" style="padding:.35rem .75rem; font-size:.75rem;" onclick="irPagina(1)">Siguiente →</button>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="card" style="overflow-x:auto;">
        <table id="tabla-pagos" style="width:100%; border-collapse:collapse; font-size:.82rem;">
            <thead>
                <tr style="border-bottom:1px solid var(--border);">
                    <th style="text-align:left; padding:.75rem 1rem; font-family:var(--mono); font-size:.7rem; color:var(--muted); font-weight:400;">ID</th>
                    <th style="text-align:left; padding:.75rem 1rem; font-family:var(--mono); font-size:.7rem; color:var(--muted); font-weight:400;">IMPORTE</th>
                    <th style="text-align:left; padding:.75rem 1rem; font-family:var(--mono); font-size:.7rem; color:var(--muted); font-weight:400;">ITEMS</th>
                    <th style="text-align:left; padding:.75rem 1rem; font-family:var(--mono); font-size:.7rem; color:var(--muted); font-weight:400;">ESTADO</th>
                    <th style="text-align:left; padding:.75rem 1rem; font-family:var(--mono); font-size:.7rem; color:var(--muted); font-weight:400;">STRIPE ID</th>
                    <th style="text-align:left; padding:.75rem 1rem; font-family:var(--mono); font-size:.7rem; color:var(--muted); font-weight:400;">FECHA</th>
                    <th style="text-align:left; padding:.75rem 1rem; font-family:var(--mono); font-size:.7rem; color:var(--muted); font-weight:400;">ACCIONES</th>
                </tr>
            </thead>
            <tbody id="tabla-body">
            </tbody>
        </table>
    </div>
</div>

{{-- ── Respuesta JSON raw ───────────────────────────────────────── --}}
<div style="margin-top:1.5rem;">
    <p class="section-title">// respuesta json raw</p>
    <div class="response-box" id="res-hist">
        <div class="response-head">
            <div class="status-dot"></div>
            <span class="status-txt"></span>
        </div>
        <pre class="response-pre"></pre>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let currentPage = 1;

    document.addEventListener('DOMContentLoaded', () => {
        // Auto-cargar si hay token
        const t = sessionStorage.getItem('bearer_token');
        if (t) cargarHistorial();
    });

    async function cargarHistorial(page = null) {
        if (page !== null) currentPage = page;
        else currentPage = parseInt(document.getElementById('page').value) || 1;

        const perPage = document.getElementById('per-page').value;
        const url     = `/api/pagos?per_page=${perPage}&page=${currentPage}`;

        const btn = document.getElementById('btn-hist');
        btn.classList.add('loading');
        btn.textContent = 'Cargando…';

        try {
            const res  = await fetch(url, {
                headers: {
                    'Authorization': 'Bearer ' + getToken(),
                    'Accept': 'application/json',
                }
            });
            const data = await res.json();

            showResponse('res-hist', res.status, data);

            if (res.ok && data.data) {
                renderTabla(data);
                document.getElementById('historial-container').style.display = 'block';
            }
        } catch(e) {
            showResponse('res-hist', 0, { error: e.message });
        } finally {
            btn.classList.remove('loading');
            btn.textContent = btn.dataset.label;
        }
    }

    function renderTabla(data) {
        const tbody   = document.getElementById('tabla-body');
        const pagText = document.getElementById('pag-text');

        pagText.textContent = `Mostrando ${data.from ?? 0}–${data.to ?? 0} de ${data.total} pagos — Página ${data.current_page} / ${data.last_page}`;

        if (!data.data.length) {
            tbody.innerHTML = `<tr><td colspan="7" style="padding:2rem 1rem; text-align:center; color:var(--muted); font-family:var(--mono); font-size:.8rem;">Sin resultados</td></tr>`;
            return;
        }

        tbody.innerHTML = data.data.map(pago => {
            const estadoClass = pago.estado || 'pendiente';
            const fecha = new Date(pago.created_at).toLocaleDateString('es-ES', {
                day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit'
            });
            const stripeShort = pago.stripe_payment_intent_id
                ? pago.stripe_payment_intent_id.substring(0, 20) + '…'
                : '—';

            return `
                <tr style="border-bottom:1px solid var(--border); transition: background .1s;" 
                    onmouseover="this.style.background='rgba(255,255,255,.02)'"
                    onmouseout="this.style.background='transparent'">
                    <td style="padding:.75rem 1rem; font-family:var(--mono); color:var(--muted);">#${pago.id}</td>
                    <td style="padding:.75rem 1rem; font-family:var(--mono); font-weight:700; color:var(--text);">€${parseFloat(pago.amount).toFixed(2)}</td>
                    <td style="padding:.75rem 1rem; color:var(--muted); font-size:.78rem; max-width:220px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="${pago.detalles_items}">${pago.detalles_items}</td>
                    <td style="padding:.75rem 1rem;"><span class="estado ${estadoClass}">${estadoClass}</span></td>
                    <td style="padding:.75rem 1rem; font-family:var(--mono); font-size:.72rem; color:var(--muted);">${stripeShort}</td>
                    <td style="padding:.75rem 1rem; font-family:var(--mono); font-size:.75rem; color:var(--muted);">${fecha}</td>
                    <td style="padding:.75rem 1rem;">
                        <button class="btn" style="padding:.3rem .7rem; font-size:.72rem;" onclick="usarPagoId(${pago.id})">
                            Usar ID
                        </button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function usarPagoId(id) {
        sessionStorage.setItem('pago_id', id);
        showToast(`pago_id ${id} guardado ✓ — ve a Pagos para usarlo`);
    }

    function irPagina(delta) {
        const newPage = currentPage + delta;
        if (newPage < 1) return;
        document.getElementById('page').value = newPage;
        cargarHistorial(newPage);
    }
</script>
@endsection

@extends('pagos.layout')

@section('content')

{{-- ── Token Bearer ─────────────────────────────────────────────── --}}
<div class="token-bar">
    <label>BEARER TOKEN</label>
    <input id="token-input" type="text" placeholder="Pega tu token de Sanctum aquí…" />
    <button onclick="saveToken()">Guardar</button>
</div>

{{-- ══════════════════════════════════════════════════════════════════
     1. CREAR INTENTO DE PAGO
═══════════════════════════════════════════════════════════════════ --}}
<p class="section-title">// 01 — crear intento</p>

<div class="card">
    <div class="card-head">
        <span class="method post">POST</span>
        <h2>Crear Intento de Pago</h2>
        <span class="url">/api/pagos/crear-intento</span>
    </div>
    <div class="card-body">
        <div class="tip">
            Devuelve un <strong>clientSecret</strong> para Stripe.js y un <strong>pago_id</strong>
            que se guarda automáticamente para usarlo en los siguientes endpoints.
        </div>

        <div class="row">
            <div class="field">
                <label>items[] — IDs de los trajes (separados por coma)</label>
                <input type="text" id="ci-items" value="1, 2" placeholder="1, 2, 3">
            </div>
            <div class="row">
                <div class="field">
                    <label>evento_id (opcional)</label>
                    <input type="number" id="ci-evento" placeholder="null">
                </div>
                <div class="field">
                    <label>residencia_id (opcional)</label>
                    <input type="number" id="ci-residencia" placeholder="null">
                </div>
            </div>
        </div>

        <button id="btn-ci" data-label="Crear Intento" class="btn" onclick="crearIntento()">
            ▶ Crear Intento
        </button>

        <div class="response-box" id="res-ci">
            <div class="response-head">
                <div class="status-dot"></div>
                <span class="status-txt"></span>
            </div>
            <pre class="response-pre"></pre>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════
     2. CONFIRMAR PAGO
═══════════════════════════════════════════════════════════════════ --}}
<p class="section-title">// 02 — confirmar pago</p>

<div class="card">
    <div class="card-head">
        <span class="method post">POST</span>
        <h2>Confirmar Pago</h2>
        <span class="url">/api/pagos/{id}/confirmar</span>
    </div>
    <div class="card-body">
        <div class="tip">
            Verifica el estado real contra Stripe y marca el pago como <strong>pagado</strong>.
            El pago_id se rellena solo si antes creaste un intento en esta sesión.
        </div>

        <div class="field">
            <label>pago_id</label>
            <input type="number" id="conf-id" class="auto-pago-id" placeholder="Se rellena automáticamente">
        </div>

        <button id="btn-conf" data-label="Confirmar Pago" class="btn" onclick="confirmarPago()">
            ▶ Confirmar Pago
        </button>

        <div class="response-box" id="res-conf">
            <div class="response-head">
                <div class="status-dot"></div>
                <span class="status-txt"></span>
            </div>
            <pre class="response-pre"></pre>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════
     3. DETALLE DE UN PAGO
═══════════════════════════════════════════════════════════════════ --}}
<p class="section-title">// 03 — detalle de pago</p>

<div class="card">
    <div class="card-head">
        <span class="method get">GET</span>
        <h2>Detalle de un Pago</h2>
        <span class="url">/api/pagos/{id}</span>
    </div>
    <div class="card-body">
        <div class="field">
            <label>pago_id</label>
            <input type="number" id="det-id" class="auto-pago-id" placeholder="ID del pago">
        </div>

        <button id="btn-det" data-label="Ver Detalle" class="btn" onclick="detallePago()">
            ▶ Ver Detalle
        </button>

        <div class="response-box" id="res-det">
            <div class="response-head">
                <div class="status-dot"></div>
                <span class="status-txt"></span>
            </div>
            <pre class="response-pre"></pre>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════
     4. REEMBOLSO
═══════════════════════════════════════════════════════════════════ --}}
<p class="section-title">// 04 — reembolso</p>

<div class="card">
    <div class="card-head">
        <span class="method post">POST</span>
        <h2>Solicitar Reembolso</h2>
        <span class="url">/api/pagos/{id}/reembolso</span>
    </div>
    <div class="card-body">
        <div class="tip">
            Solo funciona si el pago está en estado <strong>pagado</strong>.
            Reembolso total — Stripe lo procesa en sandbox al instante.
        </div>

        <div class="row">
            <div class="field">
                <label>pago_id</label>
                <input type="number" id="ref-id" class="auto-pago-id" placeholder="ID del pago">
            </div>
            <div class="field">
                <label>motivo (opcional)</label>
                <input type="text" id="ref-motivo" placeholder="El cliente no está satisfecho…">
            </div>
        </div>

        <button id="btn-ref" data-label="Solicitar Reembolso" class="btn danger" onclick="solicitarReembolso()">
            ▶ Solicitar Reembolso
        </button>

        <div class="response-box" id="res-ref">
            <div class="response-head">
                <div class="status-dot"></div>
                <span class="status-txt"></span>
            </div>
            <pre class="response-pre"></pre>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Rellenar auto-pago-id al cargar si hay en sessionStorage
    document.addEventListener('DOMContentLoaded', () => {
        const saved = sessionStorage.getItem('pago_id');
        if (saved) {
            document.querySelectorAll('.auto-pago-id').forEach(el => el.value = saved);
        }
    });

    // ── 1. Crear Intento ───────────────────────────────────────────────────
    function crearIntento() {
        const itemsRaw = document.getElementById('ci-items').value;
        const items    = itemsRaw.split(',').map(x => parseInt(x.trim())).filter(Boolean);
        const eventoId    = document.getElementById('ci-evento').value    || null;
        const residenciaId = document.getElementById('ci-residencia').value || null;

        const body = { items };
        if (eventoId)    body.evento_id     = parseInt(eventoId);
        if (residenciaId) body.residencia_id = parseInt(residenciaId);

        apiCall('POST', '/api/pagos/crear-intento', body, 'res-ci', 'btn-ci');
    }

    // ── 2. Confirmar Pago ──────────────────────────────────────────────────
    function confirmarPago() {
        const id = document.getElementById('conf-id').value;
        if (!id) return showToast('⚠ Introduce un pago_id');
        apiCall('POST', `/api/pagos/${id}/confirmar`, null, 'res-conf', 'btn-conf');
    }

    // ── 3. Detalle ─────────────────────────────────────────────────────────
    function detallePago() {
        const id = document.getElementById('det-id').value;
        if (!id) return showToast('⚠ Introduce un pago_id');
        apiCall('GET', `/api/pagos/${id}`, null, 'res-det', 'btn-det');
    }

    // ── 4. Reembolso ───────────────────────────────────────────────────────
    function solicitarReembolso() {
        const id     = document.getElementById('ref-id').value;
        const motivo = document.getElementById('ref-motivo').value;
        if (!id) return showToast('⚠ Introduce un pago_id');

        const body = {};
        if (motivo) body.motivo = motivo;

        apiCall('POST', `/api/pagos/${id}/reembolso`, body, 'res-ref', 'btn-ref');
    }
</script>
@endsection

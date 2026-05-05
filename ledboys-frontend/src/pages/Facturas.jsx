import React, { useState, useEffect } from "react";
import jsPDF from "jspdf";
import "../styles/facturas.less";

export default function Facturas() {
    const [pagos, setPagos]     = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError]     = useState(null);

    useEffect(() => {
        const token = localStorage.getItem("token");
        fetch("/api/pagos", {
            headers: { "Authorization": `Bearer ${token}`, "Accept": "application/json" },
        })
            .then(r => r.json())
            .then(data => {
                setPagos(data.data || data);
                setLoading(false);
            })
            .catch(() => { setError("Error al cargar las facturas."); setLoading(false); });
    }, []);

    const generarPDF = (pago) => {
        const user = JSON.parse(localStorage.getItem("user") || "{}");
        const doc  = new jsPDF({ unit: "mm", format: "a4" });
        const W    = 210;

        // ── FONDO ──
        doc.setFillColor(10, 10, 10);
        doc.rect(0, 0, W, 297, "F");

        // ── CABECERA dorada ──
        doc.setFillColor(201, 168, 76);
        doc.rect(0, 0, W, 2, "F");

        // ── LOGO texto ──
        doc.setFont("helvetica", "bold");
        doc.setFontSize(28);
        doc.setTextColor(201, 168, 76);
        doc.text("LEDBOYSS", 20, 22);
        doc.setFontSize(10);
        doc.setTextColor(136, 136, 136);
        doc.text("& LEDGIRLSS Performance", 20, 29);

        // ── FACTURA título ──
        doc.setFontSize(32);
        doc.setFont("helvetica", "bold");
        doc.setTextColor(255, 255, 255);
        doc.text("FACTURA", W - 20, 22, { align: "right" });

        // ── Datos factura ──
        doc.setFontSize(9);
        doc.setTextColor(136, 136, 136);
        doc.text(`Nº Factura:`, W - 70, 30, { align: "left" });
        doc.setTextColor(201, 168, 76);
        doc.text(`#${String(pago.id).padStart(6, "0")}`, W - 20, 30, { align: "right" });

        doc.setTextColor(136, 136, 136);
        doc.text(`Fecha:`, W - 70, 36);
        doc.setTextColor(255, 255, 255);
        doc.text(new Date(pago.created_at).toLocaleDateString("es-ES"), W - 20, 36, { align: "right" });

        doc.setTextColor(136, 136, 136);
        doc.text(`Estado:`, W - 70, 42);
        const estadoColor = pago.estado === "pagado" ? [80, 200, 120] : [201, 168, 76];
        doc.setTextColor(...estadoColor);
        doc.text(pago.estado.toUpperCase(), W - 20, 42, { align: "right" });

        // ── Línea separadora ──
        doc.setDrawColor(201, 168, 76);
        doc.setLineWidth(0.3);
        doc.line(20, 50, W - 20, 50);

        // ── Datos cliente ──
        doc.setFontSize(8);
        doc.setTextColor(136, 136, 136);
        doc.text("FACTURADO A", 20, 60);
        doc.setFontSize(11);
        doc.setTextColor(255, 255, 255);
        doc.setFont("helvetica", "bold");
        doc.text(user.name || "Cliente", 20, 67);
        doc.setFont("helvetica", "normal");
        doc.setFontSize(9);
        doc.setTextColor(136, 136, 136);
        doc.text(user.email || "", 20, 73);

        // ── Tabla items ──
        const tableY = 90;

        // Header tabla
        doc.setFillColor(25, 25, 25);
        doc.rect(20, tableY - 6, W - 40, 10, "F");
        doc.setFontSize(8);
        doc.setTextColor(201, 168, 76);
        doc.setFont("helvetica", "bold");
        doc.text("DESCRIPCIÓN", 25, tableY);
        doc.text("CANT.", 120, tableY, { align: "center" });
        doc.text("PRECIO UNIT.", 155, tableY, { align: "center" });
        doc.text("TOTAL", W - 25, tableY, { align: "right" });

        // Items
        const items = pago.detalles_items
            ? pago.detalles_items.split(",").map(s => s.trim()).filter(Boolean)
            : ["Servicio LED"];

        let y = tableY + 10;
        doc.setFont("helvetica", "normal");

        items.forEach((item, i) => {
            if (i % 2 === 0) {
                doc.setFillColor(15, 15, 15);
                doc.rect(20, y - 5, W - 40, 10, "F");
            }
            const precioUnit = items.length > 0 ? (pago.amount / items.length).toFixed(2) : pago.amount;
            doc.setTextColor(255, 255, 255);
            doc.setFontSize(9);
            doc.text(item, 25, y);
            doc.setTextColor(136, 136, 136);
            doc.text("1", 120, y, { align: "center" });
            doc.text(`${precioUnit}€`, 155, y, { align: "center" });
            doc.setTextColor(255, 255, 255);
            doc.text(`${precioUnit}€`, W - 25, y, { align: "right" });
            y += 12;
        });

        // Línea total
        doc.setDrawColor(201, 168, 76);
        doc.setLineWidth(0.3);
        doc.line(20, y + 2, W - 20, y + 2);

        // Total
        y += 12;
        doc.setFontSize(10);
        doc.setTextColor(136, 136, 136);
        doc.text("SUBTOTAL", W - 70, y);
        doc.setTextColor(255, 255, 255);
        doc.text(`${pago.amount}€`, W - 25, y, { align: "right" });

        y += 8;
        doc.setFontSize(13);
        doc.setFont("helvetica", "bold");
        doc.setTextColor(201, 168, 76);
        doc.text("TOTAL", W - 70, y);
        doc.setFontSize(16);
        doc.text(`${pago.amount}€`, W - 25, y, { align: "right" });

        // ── Método de pago ──
        y += 20;
        doc.setFontSize(8);
        doc.setFont("helvetica", "normal");
        doc.setTextColor(136, 136, 136);
        doc.text("MÉTODO DE PAGO", 20, y);
        doc.setTextColor(255, 255, 255);
        doc.text("Tarjeta de crédito / Stripe", 20, y + 6);
        doc.setTextColor(136, 136, 136);
        doc.setFontSize(7);
        doc.text(`Ref. Stripe: ${pago.stripe_payment_intent_id || "—"}`, 20, y + 12);

        // ── Footer ──
        doc.setFillColor(201, 168, 76);
        doc.rect(0, 285, W, 2, "F");
        doc.setFontSize(7);
        doc.setTextColor(136, 136, 136);
        doc.text("LEDBOYSS & LEDGIRLSS Performance · info@ledboyss.com · +34 644 78 42 85", W / 2, 292, { align: "center" });

        doc.save(`factura-ledboyss-${String(pago.id).padStart(6, "0")}.pdf`);
    };

    const estadoBadge = (estado) => {
        const colores = {
            pagado:      { bg: "rgba(80,200,120,0.1)",  border: "rgba(80,200,120,0.4)",  text: "#50c878" },
            pendiente:   { bg: "rgba(201,168,76,0.1)",  border: "rgba(201,168,76,0.4)",  text: "#c9a84c" },
            fallido:     { bg: "rgba(255,80,80,0.1)",   border: "rgba(255,80,80,0.4)",   text: "#ff5050" },
            reembolsado: { bg: "rgba(100,100,255,0.1)", border: "rgba(100,100,255,0.4)", text: "#6464ff" },
        };
        const c = colores[estado] || colores.pendiente;
        return (
            <span className="factura-badge" style={{ background: c.bg, border: `1px solid ${c.border}`, color: c.text }}>
                {estado.toUpperCase()}
            </span>
        );
    };

    return (
        <div className="facturas-page">
            <div className="page-hero">
                <h1><span>MIS</span> FACTURAS</h1>
            </div>

            <div className="facturas-wrap">
                {loading && <div className="loading"><div className="loading-spinner" /></div>}
                {error   && <p className="facturas-error">{error}</p>}

                {!loading && !error && pagos.length === 0 && (
                    <div className="facturas-empty">
                        <p>No tienes facturas todavía</p>
                        <a href="/catalogo" className="hero-btn">Ver catálogo</a>
                    </div>
                )}

                {!loading && !error && pagos.length > 0 && (
                    <div className="facturas-list">
                        {/* Header */}
                        <div className="facturas-header">
                            <span>Nº Factura</span>
                            <span>Fecha</span>
                            <span>Items</span>
                            <span>Total</span>
                            <span>Estado</span>
                            <span></span>
                        </div>

                        {pagos.map(pago => (
                            <div key={pago.id} className="factura-row">
                                <span className="factura-id">#{String(pago.id).padStart(6, "0")}</span>
                                <span className="factura-fecha">
                                    {new Date(pago.created_at).toLocaleDateString("es-ES")}
                                </span>
                                <span className="factura-items">
                                    {pago.detalles_items || "—"}
                                </span>
                                <span className="factura-total">{pago.amount}€</span>
                                <span>{estadoBadge(pago.estado)}</span>
                                <button
                                    className="factura-download-btn"
                                    onClick={() => generarPDF(pago)}
                                    title="Descargar PDF"
                                >
                                    ↓ PDF
                                </button>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </div>
    );
}

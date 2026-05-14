import React, { useState, useEffect } from "react";
import { loadStripe } from "@stripe/stripe-js";
import { Elements, CardElement, useStripe, useElements } from "@stripe/react-stripe-js";
import { useCart } from "../context/CartContext";
import Footer from "../components/Footer";
import jsPDF from "jspdf";
import "../styles/checkout.less";

const stripePromise = loadStripe("pk_test_51SeyyJEWnLX7ncP0lzNrZqQuOjuHFarWUSnUkLbhFF8UMfxqUTLGZtmVBL0MuHzh5oPvl9YXZHAmxtSjT2ztjUQh00fZhU2BU8");

const EMPRESA = {
    nombre:    "LEDBOYSS & LEDGIRLSS Performance S.L.",
    cif:       "B-87654321",
    direccion: "Institut Milà i Fontanals, Igualada",
    email:     "info@ledboyss.com",
    telefono:  "+34 637 64 58 24",
};

const CARD_STYLE = {
    style: {
        base: {
            color: "#ffffff",
            fontFamily: "'Montserrat', sans-serif",
            fontSize: "15px",
            fontSmoothing: "antialiased",
            "::placeholder": { color: "transparent" },
        },
        invalid: { color: "#ff6b6b" },
    },
};

function generarPDFFactura({ pagoId, total, items, stripeRef, facturacion }) {
    const doc = new jsPDF({ unit: "mm", format: "a4" });
    const W   = 210;

    doc.setFillColor(10, 10, 10);
    doc.rect(0, 0, W, 297, "F");

    doc.setFillColor(201, 168, 76);
    doc.rect(0, 0, W, 2, "F");

    doc.setFont("helvetica", "bold");
    doc.setFontSize(26);
    doc.setTextColor(201, 168, 76);
    doc.text("LEDBOYSS", 20, 20);
    doc.setFontSize(9);
    doc.setTextColor(136, 136, 136);
    doc.text("& LEDGIRLSS Performance", 20, 27);
    doc.setFontSize(7.5);
    doc.text(`CIF: ${EMPRESA.cif}`, 20, 33);
    doc.text(EMPRESA.direccion, 20, 38);
    doc.text(EMPRESA.email, 20, 43);
    doc.text(EMPRESA.telefono, 20, 48);

    doc.setFontSize(30);
    doc.setFont("helvetica", "bold");
    doc.setTextColor(255, 255, 255);
    doc.text("FACTURA", W - 20, 20, { align: "right" });

    doc.setFontSize(8.5);
    doc.setFont("helvetica", "normal");

    const addRight = (label, value, y, valueColor = [255,255,255]) => {
        doc.setTextColor(136, 136, 136);
        doc.text(label, W - 75, y);
        doc.setTextColor(...valueColor);
        doc.text(value, W - 20, y, { align: "right" });
    };

    addRight("Nº Factura:", `#${String(pagoId).padStart(6, "0")}`, 30, [201, 168, 76]);
    addRight("Fecha:", new Date().toLocaleDateString("es-ES"), 36);
    addRight("Estado:", "PAGADO", 42, [80, 200, 120]);

    doc.setDrawColor(201, 168, 76);
    doc.setLineWidth(0.3);
    doc.line(20, 56, W - 20, 56);

    doc.setFontSize(7.5);
    doc.setTextColor(136, 136, 136);
    doc.text("FACTURADO A", 20, 65);

    doc.setFontSize(12);
    doc.setFont("helvetica", "bold");
    doc.setTextColor(255, 255, 255);
    doc.text(`${facturacion.nombre} ${facturacion.apellidos}`, 20, 73);

    doc.setFont("helvetica", "normal");
    doc.setFontSize(8.5);
    doc.setTextColor(136, 136, 136);
    doc.text(`DNI: ${facturacion.dni}`, 20, 79);
    doc.text(`Tel: ${facturacion.telefono}`, 20, 84);
    doc.text(`${facturacion.direccion}`, 20, 89);
    doc.text(`CP: ${facturacion.cp}`, 20, 94);

    const tableY = 108;
    doc.setFillColor(25, 25, 25);
    doc.rect(20, tableY - 6, W - 40, 10, "F");
    doc.setFontSize(8);
    doc.setTextColor(201, 168, 76);
    doc.setFont("helvetica", "bold");
    doc.text("DESCRIPCIÓN", 25, tableY);
    doc.text("CANT.", 120, tableY, { align: "center" });
    doc.text("PRECIO UNIT.", 155, tableY, { align: "center" });
    doc.text("TOTAL", W - 25, tableY, { align: "right" });

    let y = tableY + 10;
    doc.setFont("helvetica", "normal");

    items.forEach((item, i) => {
        if (i % 2 === 0) {
            doc.setFillColor(15, 15, 15);
            doc.rect(20, y - 5, W - 40, 10, "F");
        }
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(9);
        doc.text(item.name, 25, y);
        doc.setTextColor(136, 136, 136);
        doc.text(String(item.cantidad), 120, y, { align: "center" });
        doc.text(`${parseFloat(item.precio).toFixed(2)}€`, 155, y, { align: "center" });
        doc.setTextColor(255, 255, 255);
        doc.text(`${(item.precio * item.cantidad).toFixed(2)}€`, W - 25, y, { align: "right" });
        y += 12;
    });

    doc.setDrawColor(201, 168, 76);
    doc.setLineWidth(0.3);
    doc.line(20, y + 2, W - 20, y + 2);

    y += 12;
    doc.setFontSize(10);
    doc.setTextColor(136, 136, 136);
    doc.text("SUBTOTAL", W - 70, y);
    doc.setTextColor(255, 255, 255);
    doc.text(`${total.toFixed(2)}€`, W - 25, y, { align: "right" });

    y += 8;
    doc.setFontSize(14);
    doc.setFont("helvetica", "bold");
    doc.setTextColor(201, 168, 76);
    doc.text("TOTAL", W - 70, y);
    doc.setFontSize(18);
    doc.text(`${total.toFixed(2)}€`, W - 25, y, { align: "right" });

    y += 22;
    doc.setFontSize(8);
    doc.setFont("helvetica", "normal");
    doc.setTextColor(136, 136, 136);
    doc.text("MÉTODO DE PAGO", 20, y);
    doc.setTextColor(255, 255, 255);
    doc.text("Tarjeta de crédito / Stripe", 20, y + 6);
    doc.setFontSize(7);
    doc.setTextColor(136, 136, 136);
    doc.text(`Ref. Stripe: ${stripeRef || "—"}`, 20, y + 12);

    doc.setFillColor(201, 168, 76);
    doc.rect(0, 285, W, 2, "F");
    doc.setFontSize(7);
    doc.setTextColor(136, 136, 136);
    doc.text(`${EMPRESA.nombre} · CIF: ${EMPRESA.cif} · ${EMPRESA.email} · ${EMPRESA.telefono}`, W / 2, 292, { align: "center" });

    doc.save(`factura-ledboyss-${String(pagoId).padStart(6, "0")}.pdf`);
}

function CheckoutForm({ onSuccess }) {
    const stripe                      = useStripe();
    const elements                    = useElements();
    const { items, total, clearCart } = useCart();
    const [loading, setLoading]       = useState(false);
    const [error, setError]           = useState(null);
    const [clientSecret, setClientSecret] = useState(null);
    const [pagoId, setPagoId]         = useState(null);

    const [facturacion, setFacturacion] = useState({
        nombre: "", apellidos: "", dni: "", telefono: "", direccion: "", cp: "",
    });
    const [facturacionError, setFacturacionError] = useState("");

    useEffect(() => {
        if (items.length === 0) return;
        const token = localStorage.getItem("token");

        // Incluir trajes, accesorios y packs con sus cantidades
        const itemIds = items.flatMap(i => {
            let id = null;
            if (i.tipo === "Traje")     id = parseInt(i.id.replace("traje-", ""));
            if (i.tipo === "Accesorio") id = parseInt(i.id.replace("acc-", ""));
            if (i.tipo === "Pack")      id = parseInt(i.id.replace("pack-", ""));
            if (!id) return [];
            return Array(i.cantidad).fill(id);
        });

        fetch("/api/pagos/crear-intento", {
            method: "POST",
            headers: { "Content-Type": "application/json", "Accept": "application/json", "Authorization": `Bearer ${token}` },
            body: JSON.stringify({ items: itemIds }),
        })
            .then(r => r.json())
            .then(data => { setClientSecret(data.clientSecret); setPagoId(data.pago_id); })
            .catch(() => setError("Error al conectar con el servidor de pagos."));
    }, []);

    const handleChange = (e) => setFacturacion({ ...facturacion, [e.target.name]: e.target.value });

    const handleSubmit = async (e) => {
        e.preventDefault();

        const campos = ["nombre", "apellidos", "dni", "telefono", "direccion", "cp"];
        if (campos.some(c => !facturacion[c].trim())) {
            setFacturacionError("Por favor completa todos los datos de facturación.");
            return;
        }
        setFacturacionError("");

        if (!stripe || !elements || !clientSecret) return;
        setLoading(true);
        setError(null);

        const { error: stripeError, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
            payment_method: { card: elements.getElement(CardElement) },
        });

        if (stripeError) { setError(stripeError.message); setLoading(false); return; }

        if (paymentIntent.status === "succeeded") {
            const token = localStorage.getItem("token");
            await fetch(`/api/pagos/${pagoId}/confirmar`, {
                method: "POST",
                headers: { "Content-Type": "application/json", "Accept": "application/json", "Authorization": `Bearer ${token}` },
            });
            const totalFinal = total;
            const itemsSnap  = [...items];
            clearCart();
            onSuccess(totalFinal, pagoId, itemsSnap, paymentIntent.id, facturacion);
        }
        setLoading(false);
    };

    return (
        <div className="checkout-layout">

            <div className="checkout-left">
                <div className="checkout-billing">
                    <h3 className="checkout-section-title">Datos de facturación</h3>
                    <div className="checkout-billing-grid">
                        <div className="checkout-field">
                            <label>Nombre</label>
                            <input name="nombre" value={facturacion.nombre} onChange={handleChange} placeholder="Tu nombre" />
                        </div>
                        <div className="checkout-field">
                            <label>Apellidos</label>
                            <input name="apellidos" value={facturacion.apellidos} onChange={handleChange} placeholder="Tus apellidos" />
                        </div>
                        <div className="checkout-field">
                            <label>DNI / NIF</label>
                            <input name="dni" value={facturacion.dni} onChange={handleChange} placeholder="12345678A" />
                        </div>
                        <div className="checkout-field">
                            <label>Teléfono</label>
                            <input name="telefono" value={facturacion.telefono} onChange={handleChange} placeholder="666 000 000" />
                        </div>
                        <div className="checkout-field checkout-field--full">
                            <label>Dirección</label>
                            <input name="direccion" value={facturacion.direccion} onChange={handleChange} placeholder="Calle, número, piso..." />
                        </div>
                        <div className="checkout-field">
                            <label>Código postal</label>
                            <input name="cp" value={facturacion.cp} onChange={handleChange} placeholder="08001" />
                        </div>
                    </div>
                    {facturacionError && <div className="checkout-error">{facturacionError}</div>}
                </div>

                <div className="checkout-payment">
                    <h3 className="checkout-section-title">Datos de pago</h3>
                    <div className="checkout-card-wrap">
                        <div className="checkout-card-label">Número de tarjeta</div>
                        <CardElement options={CARD_STYLE} />
                    </div>
                    <p className="checkout-test-hint">
                        Modo test — usa <strong>4242 4242 4242 4242</strong>, fecha futura y cualquier CVC
                    </p>
                    {error && <div className="checkout-error">{error}</div>}
                    <button type="button" className="checkout-btn" disabled={!stripe || !clientSecret || loading} onClick={handleSubmit}>
                        {loading ? <span className="checkout-spinner" /> : `Pagar ${total.toFixed(2)}€`}
                    </button>
                    <div className="checkout-secure">🔒 Pago seguro procesado por Stripe</div>
                    <div className="checkout-payment-methods">
                        <span className="checkout-pm-label">Métodos aceptados</span>
                        <div className="checkout-pm-icons">
                            <span className="checkout-pm-icon checkout-pm-visa">VISA</span>
                            <span className="checkout-pm-icon checkout-pm-mc">MC</span>
                            <span className="checkout-pm-icon checkout-pm-amex">AMEX</span>
                            <span className="checkout-pm-icon checkout-pm-stripe">Stripe</span>
                        </div>
                    </div>
                </div>
            </div>

            <div className="checkout-right">
                <h3 className="checkout-section-title">Resumen del pedido</h3>
                <div className="checkout-items">
                    {items.map(item => (
                        <div key={item.id} className="checkout-item">
                            <div className="checkout-item-img">
                                {item.img ? <img src={item.img} alt={item.name} /> : <div className="checkout-item-img-placeholder" />}
                            </div>
                            <div className="checkout-item-info">
                                <span className="checkout-item-name">{item.name}</span>
                                <span className="checkout-item-tipo">{item.tipo}</span>
                                <span className="checkout-item-qty">
                                    {item.cantidad} {item.cantidad === 1 ? "unidad" : "unidades"} × {parseFloat(item.precio).toFixed(2)}€
                                </span>
                            </div>
                            <div className="checkout-item-price">{(item.precio * item.cantidad).toFixed(2)}€</div>
                        </div>
                    ))}
                </div>
                <div className="checkout-total">
                    <span>Total</span>
                    <span className="checkout-total-price">{total.toFixed(2)}€</span>
                </div>
            </div>

        </div>
    );
}

export default function Checkout() {
    const { items }                     = useCart();
    const [success, setSuccess]         = useState(false);
    const [totalPagado, setTotalPagado] = useState(0);
    const [pagoIdFinal, setPagoIdFinal] = useState(null);
    const [itemsFinal, setItemsFinal]   = useState([]);
    const [stripeRef, setStripeRef]     = useState(null);
    const [facturacionFinal, setFacturacionFinal] = useState(null);

    const handleSuccess = (total, pagoId, items, stripeId, facturacion) => {
        setTotalPagado(total);
        setPagoIdFinal(pagoId);
        setItemsFinal(items);
        setStripeRef(stripeId);
        setFacturacionFinal(facturacion);
        setSuccess(true);
    };

    if (success) {
        return (
            <div className="checkout-page">
                <div className="checkout-success">
                    <div className="checkout-success-icon">✓</div>
                    <h2>¡Gracias por tu compra!</h2>
                    <p className="checkout-success-brand">LEDBOYSS & LEDGIRLSS</p>
                    <div className="gold-divider" />
                    <p className="checkout-success-msg">
                        Tu reserva ha sido confirmada con éxito.<br />
                        Nuestro equipo se pondrá en contacto contigo en breve para coordinar todos los detalles del evento.
                    </p>
                    <p className="checkout-success-total">
                        Total pagado: <span>{totalPagado.toFixed(2)}€</span>
                    </p>
                    <div className="checkout-success-actions">
                        <button
                            className="checkout-pdf-btn"
                            onClick={() => generarPDFFactura({ pagoId: pagoIdFinal, total: totalPagado, items: itemsFinal, stripeRef, facturacion: facturacionFinal })}
                        >
                            ↓ Descargar factura PDF
                        </button>
                        <a href="/" className="hero-btn">Volver al inicio</a>
                    </div>
                </div>
                <Footer />
            </div>
        );
    }

    if (items.length === 0) {
        return (
            <div className="checkout-page">
                <div className="checkout-empty">
                    <p>Tu carrito está vacío</p>
                    <a href="/catalogo" className="hero-btn">Ver catálogo</a>
                </div>
                <Footer />
            </div>
        );
    }

    return (
        <div className="checkout-page">
            <div className="page-hero">
                <h1><span>CHECK</span>OUT</h1>
            </div>
            <div className="checkout-wrap">
                <Elements stripe={stripePromise}>
                    <CheckoutForm onSuccess={handleSuccess} />
                </Elements>
            </div>
            <Footer />
        </div>
    );
}

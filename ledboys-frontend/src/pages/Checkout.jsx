import React, { useState, useEffect, useRef } from "react";
import { loadStripe } from "@stripe/stripe-js";
import { Elements, CardNumberElement, CardExpiryElement, CardCvcElement, useStripe, useElements } from "@stripe/react-stripe-js";
import { useCart } from "../context/CartContext";
import Footer from "../components/Footer";
import jsPDF from "jspdf";
import emailjs from "@emailjs/browser";
import PhoneInput, { isValidPhoneNumber } from "react-phone-number-input";
import "react-phone-number-input/style.css";
import "../styles/checkout.less";

const stripePromise = loadStripe("pk_test_51SeyyJEWnLX7ncP0lzNrZqQuOjuHFarWUSnUkLbhFF8UMfxqUTLGZtmVBL0MuHzh5oPvl9YXZHAmxtSjT2ztjUQh00fZhU2BU8");

const EMPRESA = {
    nombre:    "LEDBOYSS & LEDGIRLSS Performance S.L.",
    cif:       "B-87654321",
    direccion: "Institut Milà i Fontanals, Igualada",
    email:     "info@ledboyss.com",
    telefono:  "+34 637 64 58 24",
};

const EMAILJS = {
    serviceId:  "service_zxnnhtn",
    templateId: "template_jtumtr6",
    publicKey:  "Bx_dybjuI-eWS6agH",
};

const FIELD_STYLE = {
    style: {
        base: {
            color: "#ffffff",
            fontFamily: "'Montserrat', sans-serif",
            fontSize: "15px",
            fontSmoothing: "antialiased",
            "::placeholder": { color: "#444" },
        },
        invalid: { color: "#ff6b6b" },
    },
};

function generarPDFFactura({ pagoId, total, desglose, items, stripeRef, facturacion, evento }) {
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
    doc.text(facturacion.direccion, 20, 89);
    doc.text(`CP: ${facturacion.cp}`, 20, 94);

    if (evento) {
        doc.setFontSize(7.5);
        doc.setTextColor(136, 136, 136);
        doc.text("DATOS DEL EVENTO", W - 90, 65);
        doc.setFontSize(8.5);
        doc.setTextColor(255, 255, 255);
        doc.text(`Fecha: ${evento.fecha}`, W - 90, 73);
        doc.text(`Hora: ${evento.hora}`, W - 90, 79);
        doc.setTextColor(136, 136, 136);
        doc.text(`Lugar: ${evento.direccion_evento}`, W - 90, 85);
    }

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
    doc.setFontSize(9);
    doc.setFont("helvetica", "normal");
    doc.setTextColor(136, 136, 136);
    doc.text("Base imponible:", W - 70, y);
    doc.setTextColor(255, 255, 255);
    doc.text(`${parseFloat(desglose?.base_imponible || total).toFixed(2)}€`, W - 25, y, { align: "right" });

    y += 7;
    doc.setTextColor(136, 136, 136);
    doc.text(`IVA (${desglose?.iva_porcentaje || 21}%):`, W - 70, y);
    doc.setTextColor(255, 255, 255);
    doc.text(`${parseFloat(desglose?.cuota_iva || 0).toFixed(2)}€`, W - 25, y, { align: "right" });

    y += 9;
    doc.setFontSize(10);
    doc.setFont("helvetica", "bold");
    doc.setTextColor(201, 168, 76);
    doc.text("Total (IVA incl):", W - 68, y);
    doc.setFontSize(13);
    doc.text(`${parseFloat(desglose?.total || total).toFixed(2)}€`, W - 20, y, { align: "right" });

    y += 20;
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

async function enviarEmailConfirmacion({ facturacion, pagoId, total, userEmail }) {
    try {
        await emailjs.send(
            EMAILJS.serviceId,
            EMAILJS.templateId,
            {
                to_email:   userEmail,
                to_name:    `${facturacion.nombre} ${facturacion.apellidos}`,
                factura_id: `#${String(pagoId).padStart(6, "0")}`,
                total:      parseFloat(total).toFixed(2),
            },
            EMAILJS.publicKey
        );
    } catch (err) {
        console.error("Error al enviar email:", err);
    }
}

function CheckoutForm({ onSuccess }) {
    const stripe                      = useStripe();
    const elements                    = useElements();
    const { items, total, clearCart } = useCart();
    const [loading, setLoading]       = useState(false);
    const [error, setError]           = useState(null);

    const [facturacion, setFacturacion] = useState({
        nombre: "", apellidos: "", dni: "", telefono: "", direccion: "", cp: "",
    });
    const [evento, setEvento] = useState({
        fecha: "", hora: "", direccion_evento: "",
    });
    const [facturacionError, setFacturacionError] = useState("");
    const phoneRef = useRef(null);

    // Controlar longitud del teléfono directamente en el DOM
    useEffect(() => {
        const wrap = phoneRef.current;
        if (!wrap) return;
        const input = wrap.querySelector("input");
        if (!input) return;
        const handler = (e) => {
            const digits = (input.value || "").replace(/[^0-9]/g, "");
            if (digits.length >= 11 && e.key !== "Backspace" && e.key !== "Delete" && e.key !== "ArrowLeft" && e.key !== "ArrowRight" && e.key !== "Tab") {
                e.preventDefault();
            }
            if (!/[0-9]/.test(e.key) && e.key !== "Backspace" && e.key !== "Delete" && e.key !== "ArrowLeft" && e.key !== "ArrowRight" && e.key !== "Tab") {
                e.preventDefault();
            }
        };
        input.addEventListener("keydown", handler);
        return () => input.removeEventListener("keydown", handler);
    }, []);

    const handleChange = (e) => setFacturacion({ ...facturacion, [e.target.name]: e.target.value });
    const handleEvento = (e) => setEvento({ ...evento, [e.target.name]: e.target.value });

    const handleSubmit = async (e) => {
        e.preventDefault();

        // Validaciones
        const camposFact = ["nombre", "apellidos", "dni", "telefono", "direccion", "cp"];
        if (camposFact.some(c => !facturacion[c].trim())) {
            setFacturacionError("Por favor completa todos los datos de facturación.");
            return;
        }

        const dniRegex = /^[0-9]{8}[A-Za-z]$|^[XYZxyz][0-9]{7}[A-Za-z]$/;
        if (!dniRegex.test(facturacion.dni.trim())) {
            setFacturacionError("El DNI/NIE no es válido. Formato: 12345678A o X1234567A");
            return;
        }

        if (!facturacion.telefono || !isValidPhoneNumber(facturacion.telefono)) {
            setFacturacionError("El teléfono no es válido para el país seleccionado.");
            return;
        }

        if (!/^\d{5}$/.test(facturacion.cp.trim())) {
            setFacturacionError("El código postal debe tener 5 dígitos.");
            return;
        }

        if (!evento.fecha || !evento.hora || !evento.direccion_evento.trim()) {
            setFacturacionError("Por favor completa todos los datos del evento.");
            return;
        }
        setFacturacionError("");

        if (!stripe || !elements) return;
        setLoading(true);
        setError(null);

        const token = localStorage.getItem("token");

        const itemIds = items.flatMap(i => {
            let id = null;
            if (i.tipo === "Traje")     id = parseInt(i.id.replace("traje-", ""));
            if (i.tipo === "Accesorio") id = parseInt(i.id.replace("acc-", ""));
            if (i.tipo === "Pack")      id = parseInt(i.id.replace("pack-", ""));
            if (!id) return [];
            return Array(i.cantidad).fill(id);
        });

        // Crear intento con todos los datos ya rellenos
        let clientSecret, pagoId, desglose;
        try {
            const res = await fetch("/api/pagos/crear-intento", {
                method: "POST",
                headers: { "Content-Type": "application/json", "Accept": "application/json", "Authorization": `Bearer ${token}` },
                body: JSON.stringify({
                    items:                 itemIds,
                    fecha:                 evento.fecha,
                    hora:                  evento.hora,
                    ubicacion:             evento.direccion_evento,
                    nombre_facturacion:    facturacion.nombre,
                    apellidos_facturacion: facturacion.apellidos,
                    dni:                   facturacion.dni,
                    telefono_facturacion:  facturacion.telefono,
                    direccion:             facturacion.direccion,
                    cp:                    facturacion.cp,
                }),
            });
            const data = await res.json();
            if (!data.clientSecret) {
                setError("Error al crear el intento de pago.");
                setLoading(false);
                return;
            }
            clientSecret = data.clientSecret;
            pagoId       = data.pago_id;
            desglose     = data.desglose;
        } catch {
            setError("Error al conectar con el servidor de pagos.");
            setLoading(false);
            return;
        }

        const cardNumber = elements.getElement(CardNumberElement);

        const { error: stripeError, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
            payment_method: { card: cardNumber },
        });

        if (stripeError) { setError(stripeError.message); setLoading(false); return; }

        if (paymentIntent.status === "succeeded") {
            await fetch(`/api/pagos/${pagoId}/confirmar`, {
                method: "POST",
                headers: { "Content-Type": "application/json", "Accept": "application/json", "Authorization": `Bearer ${token}` },
            });

            const totalFinal = desglose?.total || total;
            const itemsSnap  = [...items];
            const user       = JSON.parse(localStorage.getItem("user") || "{}");

            await enviarEmailConfirmacion({
                facturacion,
                pagoId,
                total: totalFinal,
                userEmail: user.email || "",
            });

            clearCart();
            onSuccess(totalFinal, pagoId, itemsSnap, paymentIntent.id, facturacion, desglose, evento);
        }
        setLoading(false);
    };

    const totalConIva = total;
    const trajes      = items.filter(i => i.tipo === "Traje");
    const extras      = items.filter(i => i.tipo !== "Traje");

    return (
        <div className="checkout-layout">
            <div className="checkout-left">

                {/* DATOS DE FACTURACIÓN */}
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
                            <input
                                name="dni"
                                value={facturacion.dni}
                                onChange={e => {
                                    const val = e.target.value.toUpperCase().replace(/[^0-9A-Z]/g, "");
                                    if (val.length <= 9) setFacturacion({ ...facturacion, dni: val });
                                }}
                                placeholder="12345678A"
                                maxLength={9}
                            />
                        </div>
                        <div className="checkout-field">
                            <label>Teléfono</label>
                            <div className="checkout-phone-wrap" ref={phoneRef}>
                                <PhoneInput
                                    defaultCountry="ES"
                                    international
                                    withCountryCallingCode
                                    value={facturacion.telefono}
                                    onChange={val => {
                                        if (!val) { setFacturacion({ ...facturacion, telefono: "" }); return; }
                                        const digits = val.replace(/[^0-9]/g, "");
                                        if (digits.length <= 12) setFacturacion({ ...facturacion, telefono: val });
                                    }}
                                    placeholder="666 000 000"
                                />
                            </div>
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
                </div>

                {/* DATOS DEL EVENTO */}
                <div className="checkout-billing">
                    <h3 className="checkout-section-title">Datos del evento</h3>
                    <div className="checkout-billing-grid">
                        <div className="checkout-field">
                            <label>Fecha del evento</label>
                            <input name="fecha" type="date" value={evento.fecha} onChange={handleEvento} min={new Date().toISOString().split("T")[0]} />
                        </div>
                        <div className="checkout-field">
                            <label>Hora del evento</label>
                            <input name="hora" type="time" value={evento.hora} onChange={handleEvento} />
                        </div>
                        <div className="checkout-field checkout-field--full">
                            <label>Dirección del evento</label>
                            <input name="direccion_evento" value={evento.direccion_evento} onChange={handleEvento} placeholder="Lugar donde se realizará el evento" />
                        </div>
                    </div>
                    {facturacionError && <div className="checkout-error">{facturacionError}</div>}
                </div>

                {/* DATOS DE PAGO */}
                <div className="checkout-payment">
                    <h3 className="checkout-section-title">Datos de pago</h3>

                    <div className="checkout-field checkout-field--full">
                        <label>Número de tarjeta</label>
                        <div className="checkout-card-wrap">
                            <CardNumberElement options={FIELD_STYLE} />
                        </div>
                    </div>

                    <div className="checkout-billing-grid">
                        <div className="checkout-field">
                            <label>Fecha de caducidad</label>
                            <div className="checkout-card-wrap">
                                <CardExpiryElement options={FIELD_STYLE} />
                            </div>
                        </div>
                        <div className="checkout-field">
                            <label>CVV</label>
                            <div className="checkout-card-wrap">
                                <CardCvcElement options={FIELD_STYLE} />
                            </div>
                        </div>
                    </div>

                    <p className="checkout-test-hint">
                        Modo test — usa <strong>4242 4242 4242 4242</strong>, fecha futura y cualquier CVC
                    </p>

                    {error && <div className="checkout-error">{error}</div>}

                    <button type="button" className="checkout-btn" disabled={!stripe || loading} onClick={handleSubmit}>
                        {loading ? <span className="checkout-spinner" /> : `Pagar ${parseFloat(totalConIva).toFixed(2)}€`}
                    </button>

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

            {/* RESUMEN */}
            <div className="checkout-right">
                <h3 className="checkout-section-title">Resumen del pedido</h3>
                <div className="checkout-items">
                    {trajes.map(traje => (
                        <div key={traje.id}>
                            <div className="checkout-item">
                                <div className="checkout-item-img">
                                    {traje.img ? <img src={traje.img} alt={traje.name} /> : <div className="checkout-item-img-placeholder" />}
                                </div>
                                <div className="checkout-item-info">
                                    <span className="checkout-item-name">{traje.name}</span>
                                    <span className="checkout-item-tipo">{traje.tipo}</span>
                                    <span className="checkout-item-qty">
                                        {traje.cantidad} {traje.cantidad === 1 ? "unidad" : "unidades"} × {parseFloat(traje.precio).toFixed(2)}€
                                    </span>
                                </div>
                                <div className="checkout-item-price">{(traje.precio * traje.cantidad).toFixed(2)}€</div>
                            </div>
                        </div>
                    ))}
                    {extras.length > 0 && (
                        <div className="checkout-extras-divider">Accesorios y packs</div>
                    )}
                    {extras.map(extra => (
                        <div key={extra.id} className="checkout-item checkout-item--extra">
                            <div className="checkout-item-extra-indent">↳</div>
                            <div className="checkout-item-info">
                                <span className="checkout-item-name">{extra.name}</span>
                                <span className="checkout-item-tipo">{extra.tipo}</span>
                                <span className="checkout-item-qty">
                                    {extra.cantidad} {extra.cantidad === 1 ? "unidad" : "unidades"} × {parseFloat(extra.precio).toFixed(2)}€
                                </span>
                            </div>
                            <div className="checkout-item-price">{(extra.precio * extra.cantidad).toFixed(2)}€</div>
                        </div>
                    ))}
                </div>

                <div className="checkout-total">
                    <span>Total (IVA incl.)</span>
                    <span className="checkout-total-price">{parseFloat(totalConIva).toFixed(2)}€</span>
                </div>
            </div>
        </div>
    );
}

export default function Checkout() {
    const { items }                               = useCart();
    const [success, setSuccess]                   = useState(false);
    const [totalPagado, setTotalPagado]           = useState(0);
    const [pagoIdFinal, setPagoIdFinal]           = useState(null);
    const [itemsFinal, setItemsFinal]             = useState([]);
    const [stripeRef, setStripeRef]               = useState(null);
    const [facturacionFinal, setFacturacionFinal] = useState(null);
    const [desgloseFinal, setDesgloseFinal]       = useState(null);
    const [eventoFinal, setEventoFinal]           = useState(null);

    const handleSuccess = (total, pagoId, items, stripeId, facturacion, desglose, evento) => {
        setTotalPagado(total);
        setPagoIdFinal(pagoId);
        setItemsFinal(items);
        setStripeRef(stripeId);
        setFacturacionFinal(facturacion);
        setDesgloseFinal(desglose);
        setEventoFinal(evento);
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
                        Total pagado: <span>{parseFloat(totalPagado).toFixed(2)}€</span>
                    </p>
                    <div className="checkout-success-actions">
                        <button
                            className="checkout-pdf-btn"
                            onClick={() => generarPDFFactura({
                                pagoId:      pagoIdFinal,
                                total:       totalPagado,
                                desglose:    desgloseFinal,
                                items:       itemsFinal,
                                stripeRef,
                                facturacion: facturacionFinal,
                                evento:      eventoFinal,
                            })}
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
            <div className="page-hero checkout-page-hero">
                <a href="/catalogo" className="checkout-back"><span>←</span> Volver</a>
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

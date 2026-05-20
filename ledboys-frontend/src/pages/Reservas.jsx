import React, { useState, useEffect } from "react";
import Footer from "../components/Footer";
import "../styles/reservas.less";

export default function Reservas() {
    const [reservas, setReservas]     = useState([]);
    const [historial, setHistorial]   = useState([]);
    const [loading, setLoading]       = useState(true);
    const [tab, setTab]               = useState("futuras");
    const [expanded, setExpanded]     = useState(null);

    useEffect(() => {
        const token   = localStorage.getItem("token");
        const headers = { "Accept": "application/json", "Authorization": `Bearer ${token}` };

        Promise.all([
            fetch("/api/reservas", { headers }).then(r => r.json()),
            fetch("/api/reservas?historial=true", { headers }).then(r => r.json()),
        ]).then(([futuras, pasadas]) => {
            setReservas(futuras.eventos || []);
            setHistorial(pasadas.eventos || []);
            setLoading(false);
        }).catch(() => setLoading(false));
    }, []);

    const lista = tab === "futuras" ? reservas : historial;

    const toggleExpand = (id) => setExpanded(prev => prev === id ? null : id);

    if (loading) return <div className="loading"><div className="loading-spinner" /></div>;

    return (
        <div className="reservas-page">
            <div className="page-hero">
                <h1><span>MIS</span> RESERVAS</h1>
            </div>

            <div className="reservas-wrap">

                {/* TABS */}
                <div className="reservas-tabs">
                    <button
                        className={`reservas-tab${tab === "futuras" ? " active" : ""}`}
                        onClick={() => setTab("futuras")}
                    >
                        Próximas {reservas.length > 0 && <span className="reservas-tab-count">{reservas.length}</span>}
                    </button>
                    <button
                        className={`reservas-tab${tab === "historial" ? " active" : ""}`}
                        onClick={() => setTab("historial")}
                    >
                        Historial {historial.length > 0 && <span className="reservas-tab-count">{historial.length}</span>}
                    </button>
                </div>

                {/* LISTA */}
                {lista.length === 0 ? (
                    <div className="reservas-empty">
                        <p>{tab === "futuras" ? "No tienes reservas próximas" : "No tienes reservas pasadas"}</p>
                        {tab === "futuras" && <a href="/catalogo" className="hero-btn">Ver catálogo</a>}
                    </div>
                ) : (
                    <div className="reservas-list">
                        {lista.map(reserva => (
                            <div key={reserva.id} className={`reserva-card${expanded === reserva.id ? " expanded" : ""}`}>

                                {/* CABECERA */}
                                <div className="reserva-header" onClick={() => toggleExpand(reserva.id)}>
                                    <div className="reserva-header-left">
                                        <span className={`reserva-estado reserva-estado--${reserva.estado}`}>
                                            {reserva.estado}
                                        </span>
                                        <div className="reserva-fecha-wrap">
                                            <span className="reserva-fecha">{reserva.fecha || "—"}</span>
                                            {reserva.hora && <span className="reserva-hora">{reserva.hora}</span>}
                                        </div>
                                    </div>
                                    <div className="reserva-header-right">
                                        <span className="reserva-total">{parseFloat(reserva.total_precio).toFixed(2)}€</span>
                                        <span className="reserva-toggle">{expanded === reserva.id ? "▲" : "▼"}</span>
                                    </div>
                                </div>

                                {/* DETALLE */}
                                {expanded === reserva.id && (
                                    <div className="reserva-detalle">

                                        {reserva.ubicacion && (
                                            <div className="reserva-info-row">
                                                <span className="reserva-info-label">📍 Lugar</span>
                                                <span className="reserva-info-value">{reserva.ubicacion}</span>
                                            </div>
                                        )}

                                        <div className="reserva-items">
                                            <h4>Items contratados</h4>
                                            {/* Agrupar por nombre y tipo */}
                                            {Object.values(
                                                reserva.items.reduce((acc, item) => {
                                                    const key = `${item.id}-${item.tipo}`;
                                                    if (!acc[key]) acc[key] = { ...item, cantidad: 0 };
                                                    acc[key].cantidad += item.cantidad;
                                                    return acc;
                                                }, {})
                                            ).map((item, i) => (
                                                <div key={i} className="reserva-item">
                                                    <div className="reserva-item-info">
                                                        <span className="reserva-item-nombre">{item.nombre}</span>
                                                        <span className="reserva-item-tipo">{item.tipo}</span>
                                                    </div>
                                                    <div className="reserva-item-right">
                                                        <span className="reserva-item-qty">× {item.cantidad}</span>
                                                        <span className="reserva-item-precio">
                                                            {(parseFloat(item.precio_unitario) * item.cantidad).toFixed(2)}€
                                                        </span>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>

                                        <div className="reserva-footer-total">
                                            <span>Total</span>
                                            <span>{parseFloat(reserva.total_precio).toFixed(2)}€</span>
                                        </div>
                                    </div>
                                )}
                            </div>
                        ))}
                    </div>
                )}
            </div>

            <Footer />
        </div>
    );
}

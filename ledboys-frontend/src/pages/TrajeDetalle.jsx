import React, { useState, useEffect } from "react";
import Footer from "../components/Footer";
import "../styles/trajedetalle.less";

export default function TrajeDetalle({ id }) {
    const [traje, setTraje] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError]   = useState(null);

    useEffect(() => {
        fetch(`/api/trajes/${id}`)
            .then(res => {
                if (!res.ok) throw new Error("Traje no encontrado");
                return res.json();
            })
            .then(data => { setTraje(data); setLoading(false); })
            .catch(err => { setError(err.message); setLoading(false); });
    }, [id]);

    if (loading) return <div className="loading"><div className="loading-spinner" /></div>;
    if (error)   return (
        <div className="detalle-error">
            <h2>404</h2>
            <p>{error}</p>
            <a href="/catalogo" className="hero-btn">Volver al catálogo</a>
        </div>
    );

    const img    = traje.imagen ? `/${traje.imagen}` : null;
    const genero = traje.traje?.genero ?? "—";
    const tipo   = traje.traje?.tipo_traje ?? "—";
    const stock  = traje.traje?.stock_total ?? "—";

    return (
        <div className="detalle-page">

            {/* HERO */}
            <div className="detalle-hero">
                {img
                    ? <img src={img} alt={traje.nombre} className="detalle-hero-img" />
                    : <div className="detalle-hero-placeholder" />
                }
                <div className="detalle-hero-overlay" />
                <div className="detalle-hero-content">
                    <a href="/catalogo" className="detalle-back">← Catálogo</a>
                    <h1>{traje.nombre}</h1>
                </div>
            </div>

            {/* INFO */}
            <div className="detalle-body">
                <div className="detalle-info">

                    <div className="detalle-badges">
                        <span className="detalle-badge">{genero}</span>
                        <span className="detalle-badge">{tipo === "zancos" ? "Con zancos" : "Sin zancos"}</span>
                    </div>

                    <div className="gold-divider" style={{ margin: "2rem 0" }} />

                    <div className="detalle-stats">
                        <div className="detalle-stat">
                            <span className="detalle-stat-label">Precio</span>
                            <span className="detalle-stat-value">{traje.precio}€</span>
                        </div>
                        <div className="detalle-stat">
                            <span className="detalle-stat-label">Stock</span>
                            <span className="detalle-stat-value">{stock} unidades</span>
                        </div>
                        <div className="detalle-stat">
                            <span className="detalle-stat-label">Género</span>
                            <span className="detalle-stat-value" style={{ textTransform: "capitalize" }}>{genero}</span>
                        </div>
                    </div>

                    {traje.descripcion && (
                        <p className="detalle-descripcion">{traje.descripcion}</p>
                    )}

                    <div className="detalle-actions">
                        <a href="/#contacto" className="hero-btn">Contratar</a>
                        <a href="/catalogo" className="detalle-btn-secondary">Ver más trajes</a>
                    </div>
                </div>
            </div>

            <Footer />
        </div>
    );
}

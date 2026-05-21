import React, { useState, useEffect, useRef } from "react";
import Footer from "../components/Footer";
import Configurador from "../components/Configurador";
import "../styles/trajedetalle.less";

function Lightbox({ fotos, idx, onClose, onPrev, onNext }) {
    useEffect(() => {
        const handleKey = (e) => {
            if (e.key === "Escape") onClose();
            if (e.key === "ArrowLeft") onPrev();
            if (e.key === "ArrowRight") onNext();
        };
        window.addEventListener("keydown", handleKey);
        return () => window.removeEventListener("keydown", handleKey);
    }, [idx]);

    return (
        <div className="lightbox" onClick={onClose}>
            <button className="lightbox-close" onClick={onClose}>✕</button>
            <button className="lightbox-arrow lightbox-arrow--prev" onClick={e => { e.stopPropagation(); onPrev(); }}>‹</button>
            <div className="lightbox-img-wrap" onClick={e => e.stopPropagation()}>
                <img src={`data:image/jpeg;base64,${fotos[idx].imagen}`} alt={fotos[idx].nombre} />
            </div>
            <button className="lightbox-arrow lightbox-arrow--next" onClick={e => { e.stopPropagation(); onNext(); }}>›</button>
            <div className="lightbox-counter">{idx + 1} / {fotos.length}</div>
        </div>
    );
}

function Carrusel({ fotos, onOpenLightbox }) {
    const [idx, setIdx] = useState(0);
    const timerRef      = useRef(null);

    const goTo = (i) => {
        setIdx(i);
        clearInterval(timerRef.current);
        timerRef.current = setInterval(() => setIdx(c => (c + 1) % fotos.length), 4000);
    };

    useEffect(() => {
        if (fotos.length <= 1) return;
        timerRef.current = setInterval(() => setIdx(c => (c + 1) % fotos.length), 4000);
        return () => clearInterval(timerRef.current);
    }, [fotos.length]);

    if (!fotos.length) return <div className="detalle-carrusel-empty" />;

    return (
        <div className="detalle-carrusel">
            {fotos.map((f, i) => (
                <div key={f.id} className={`detalle-carrusel-slide${i === idx ? " active" : ""}`} onClick={() => onOpenLightbox(idx)}>
                    <img src={`data:image/jpeg;base64,${f.imagen}`} alt={f.nombre} />
                    {i === idx && <div className="detalle-carrusel-zoom">🔍</div>}
                </div>
            ))}

            {fotos.length > 1 && (
                <>
                    <button className="detalle-carrusel-arrow detalle-carrusel-arrow--prev" onClick={() => goTo((idx - 1 + fotos.length) % fotos.length)}>‹</button>
                    <button className="detalle-carrusel-arrow detalle-carrusel-arrow--next" onClick={() => goTo((idx + 1) % fotos.length)}>›</button>
                </>
            )}

            {fotos.length > 1 && (
                <div className="detalle-carrusel-dots">
                    {fotos.map((_, i) => (
                        <button key={i} className={`detalle-carrusel-dot${i === idx ? " active" : ""}`} onClick={() => goTo(i)} />
                    ))}
                </div>
            )}

            {fotos.length > 1 && (
                <div className="detalle-carrusel-thumbs">
                    {fotos.map((f, i) => (
                        <div key={f.id} className={`detalle-carrusel-thumb${i === idx ? " active" : ""}`} onClick={() => goTo(i)}>
                            <img src={`data:image/jpeg;base64,${f.imagen}`} alt={f.nombre} />
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
}

export default function TrajeDetalle({ id }) {
    const [traje, setTraje]           = useState(null);
    const [fotos, setFotos]           = useState([]);
    const [loading, setLoading]       = useState(true);
    const [error, setError]           = useState(null);
    const [showConfig, setShowConfig]   = useState(false);
    const [lightboxIdx, setLightboxIdx] = useState(null);

    useEffect(() => {
        Promise.all([
            fetch(`/api/trajes/${id}`).then(r => { if (!r.ok) throw new Error("Traje no encontrado"); return r.json(); }),
            fetch(`/api/fotos/traje/${id}`).then(r => r.json()).catch(() => ({ fotos: [] })),
        ]).then(([trajeData, fotosData]) => {
            setTraje(trajeData);
            setFotos(fotosData.fotos || []);
            setLoading(false);
        }).catch(err => { setError(err.message); setLoading(false); });
    }, [id]);

    if (loading) return <div className="loading"><div className="loading-spinner" /></div>;
    if (error) return (
        <div className="detalle-error">
            <h2>404</h2>
            <p>{error}</p>
            <a href="/catalogo" className="hero-btn">Volver al catálogo</a>
        </div>
    );

    const genero        = traje.traje?.genero ?? "—";
    const tipo          = traje.traje?.tipo_traje ?? "—";
    const stock         = traje.traje?.stock_total ?? "—";
    const fotoPrincipal = fotos.find(f => f.principal) || fotos[0];

    return (
        <div className="detalle-page">

            {/* HERO — foto principal + título */}
            <div className="detalle-hero">
                {fotoPrincipal
                    ? <img src={`data:image/jpeg;base64,${fotoPrincipal.imagen}`} alt={traje.nombre} className="detalle-hero-img" />
                    : <div className="detalle-hero-placeholder" />
                }
                <div className="detalle-hero-overlay" />
                <div className="detalle-hero-content">
                    <a href="/catalogo" className="detalle-back"><span>←</span> Catálogo</a>
                    <h1>{traje.nombre}</h1>
                </div>
            </div>

            {/* SECCIÓN DETALLES — carrusel izquierda, info derecha */}
            <div className="detalle-body">
                <div className="detalle-layout">

                    {/* IZQUIERDA — carrusel */}
                    <div className="detalle-left">
                        <Carrusel fotos={fotos} onOpenLightbox={setLightboxIdx} />
                    </div>

                    {/* DERECHA — detalles */}
                    <div className="detalle-right">
                        <h2 className="detalle-section-title">DETALLES</h2>
                        <div className="gold-divider" style={{ margin: "1rem 0 1.5rem" }} />

                        <div className="detalle-badges">
                            <span className="detalle-badge">{tipo === "zancos" ? "Con zancos" : "Sin zancos"}</span>
                        </div>

                        <div className="detalle-stats">
                            <div className="detalle-stat">
                                <span className="detalle-stat-label">Precio</span>
                                <span className="detalle-stat-value">{traje.precio}€</span>
                            </div>
                            <div className="detalle-stat">
                                <span className="detalle-stat-label">Stock</span>
                                <span className="detalle-stat-value">{stock} uds.</span>
                            </div>
                        </div>

                        {traje.descripcion && (
                            <p className="detalle-descripcion">{traje.descripcion}</p>
                        )}

                        <div className="detalle-actions">
                            <button className="hero-btn" onClick={() => setShowConfig(true)}>Contratar</button>
                            <a href="/catalogo" className="detalle-btn-secondary">Ver más trajes</a>
                        </div>
                    </div>
                </div>
            </div>

            {lightboxIdx !== null && fotos.length > 0 && (
                <Lightbox
                    fotos={fotos}
                    idx={lightboxIdx}
                    onClose={() => setLightboxIdx(null)}
                    onPrev={() => setLightboxIdx(i => (i - 1 + fotos.length) % fotos.length)}
                    onNext={() => setLightboxIdx(i => (i + 1) % fotos.length)}
                />
            )}

            {showConfig && (
                <Configurador traje={traje} stock={stock} onClose={() => setShowConfig(false)} />
            )}

            <Footer />
        </div>
    );
}

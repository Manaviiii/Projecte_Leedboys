import React from "react";
import Footer from "../components/Footer";

const TIPO_CONFIG = {
    bodas: {
        title: "BODAS",
        subtitle: "Hacemos de tu boda un momento mágico e irrepetible",
        bg: "/images/angeles.jpg",
        description: "Convertimos tu boda en un espectáculo de luz y color. Nuestros performers con trajes LED iluminan la pista de baile y crean momentos únicos que tus invitados recordarán siempre.",
        items: [
            { name: "Mariachis",    img: "/images/mariachis.jpg" },
            { name: "Flower Power", img: "/images/flower_power.jpg" },
            { name: "Ángeles",      img: "/images/angeles.jpg" },
            { name: "Daft Punk",    img: "/images/daft_punk.jpg" },
        ],
    },
    discotecas: {
        title: "DISCOTECAS",
        subtitle: "La energía perfecta para tu noche",
        bg: "/images/motomamis.jpg",
        description: "Llevamos el espectáculo a otro nivel en clubs y discotecas. Nuestros performers LED se integran perfectamente con la música y el ambiente para crear una noche inolvidable.",
        items: [
            { name: "Daft Punk",       img: "/images/daft_punk.jpg" },
            { name: "Motomamis",       img: "/images/motomamis.jpg" },
            { name: "Disco Girls",     img: "/images/disco_girls.jpg" },
            { name: "Future Girls",    img: "/images/future_girls.jpg" },
            { name: "Anubis",          img: "/images/anubis.jpg" },
            { name: "Bad Bunny x Rauw", img: "/images/Bad_bunny_x_rauw.jpg" },
        ],
    },
    eventos: {
        title: "EVENTOS",
        subtitle: "Para cualquier tipo de celebración especial",
        bg: "/images/opcion_eventos.png",
        description: "Desde festivales hasta eventos corporativos, pasando por fiestas privadas y pasacalles. Adaptamos nuestro espectáculo a cualquier tipo de evento para garantizar una experiencia única.",
        items: [
            { name: "Iluminati",    img: "/images/iluminati.jpg" },
            { name: "Gladiadores",  img: "/images/gladiadores.jpg" },
            { name: "Árboles",      img: "/images/arboles.jpg" },
            { name: "Future Girls", img: "/images/future_girls.jpg" },
        ],
    },
    ledboyss: {
        title: "LEDBOYSS",
        subtitle: "Performance masculino LED de alta energía",
        bg: "/images/anubis.jpg",
        description: "Los Ledboyss son nuestro equipo masculino de performers con trajes LED de última generación. Su presencia imponente y energética transforma cualquier evento en un espectáculo visual único.",
        items: [
            { name: "Anubis",      img: "/images/anubis.jpg" },
            { name: "Gladiadores", img: "/images/gladiadores.jpg" },
            { name: "Daft Punk",   img: "/images/daft_punk.jpg" },
            { name: "Iluminati",   img: "/images/iluminati.jpg" },
            { name: "Mariachis",   img: "/images/mariachis.jpg" },
        ],
    },
    ledgirlss: {
        title: "LEDGIRLSS",
        subtitle: "Elegancia y espectáculo en cada movimiento",
        bg: "/images/future_girls.jpg",
        description: "Las Ledgirlss son nuestras performers femeninas, combinando elegancia, técnica de baile y espectaculares trajes LED para crear actuaciones de alto impacto visual.",
        items: [
            { name: "Flower Power", img: "/images/flower_power.jpg" },
            { name: "Disco Girls",  img: "/images/disco_girls.jpg" },
            { name: "Motomamis",    img: "/images/motomamis.jpg" },
            { name: "Future Girls", img: "/images/future_girls.jpg" },
            { name: "Ángeles",      img: "/images/angeles.jpg" },
        ],
    },
};

export default function TipoPage({ tipo }) {
    const config = TIPO_CONFIG[tipo] || TIPO_CONFIG["eventos"];

    return (
        <div className="tipo-page">
            <div className="tipo-hero">
                <img className="tipo-hero-bg" src={config.bg} alt={config.title} />
                <div className="tipo-hero-overlay" />
                <div className="tipo-hero-content">
                    <h1><span>{config.title.slice(0, 3)}</span>{config.title.slice(3)}</h1>
                    <p>{config.subtitle}</p>
                </div>
            </div>

            <div style={{ padding: "5rem 2rem", maxWidth: 800, margin: "0 auto", textAlign: "center" }}>
                <div className="gold-divider" />
                <p style={{ fontSize: "1rem", fontWeight: 300, lineHeight: 1.9, color: "rgba(255,255,255,0.75)" }}>
                    {config.description}
                </p>
                <div style={{ marginTop: "2.5rem" }}>
                    <a href="/catalogo" className="hero-btn">Ver catálogo completo</a>
                </div>
            </div>

            <div className="tipo-gallery">
                <div className="tipo-gallery-grid">
                    {config.items.map((item, i) => (
                        <div key={i} className="catalog-item" style={{ aspectRatio: "4/5" }}>
                            <img src={item.img} alt={item.name} />
                            <div className="catalog-item-overlay" />
                            <div className="catalog-item-info">
                                <h3>{item.name}</h3>
                            </div>
                        </div>
                    ))}
                </div>
            </div>

            <section style={{ padding: "6rem 2rem", textAlign: "center", background: "var(--gray-dark)", borderTop: "1px solid rgba(201,168,76,0.15)" }}>
                <span className="section-label">¿Interesado?</span>
                <div className="gold-divider" />
                <h2 style={{ fontFamily: "var(--font-display)", fontSize: "clamp(2rem, 5vw, 4rem)", letterSpacing: "6px", marginBottom: "2rem" }}>
                    CONTRATA <span style={{ color: "var(--gold)" }}>NUESTRO</span> SHOW
                </h2>
                <a href="/#contacto" className="hero-btn">Contactar ahora</a>
            </section>

            <Footer />
        </div>
    );
}

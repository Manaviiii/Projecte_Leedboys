import React, { useState, useEffect } from "react";
import Footer from "../components/Footer";
import "../styles/home.less";

const CATEGORIES = [
    { label: "Discotecas", href: "/tipo/discotecas", img: "/images/disco_girls.jpg" },
    { label: "Bodas",      href: "/tipo/bodas",      img: "/images/angeles.jpg" },
    { label: "Eventos",    href: "/tipo/eventos",    img: "/images/opcion_eventos.png" },
    { label: "Pasacalles", href: "/tipo/eventos",    img: "/images/arboles.jpg" },
];

function MapaUbicacion() {
    useEffect(() => {
        if (!document.getElementById("leaflet-css")) {
            const link = document.createElement("link");
            link.id   = "leaflet-css";
            link.rel  = "stylesheet";
            link.href = "https://unpkg.com/leaflet@1.9.4/dist/leaflet.css";
            document.head.appendChild(link);
        }

        const script = document.createElement("script");
        script.src = "https://unpkg.com/leaflet@1.9.4/dist/leaflet.js";
        script.onload = () => {
            const L = window.L;
            if (!L || document.getElementById("mapa-ubicacion")?._leaflet_id) return;

            const map = L.map("mapa-ubicacion", {
                center:          [41.5842, 1.6010], // Institut Milà i Fontanals
                zoom:            15,
                scrollWheelZoom: false,
                zoomControl:     true,
            });

            L.tileLayer("https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png", {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> © <a href="https://carto.com/">CARTO</a>',
                maxZoom: 19,
            }).addTo(map);

            const goldIcon = L.divIcon({
                className: "",
                html: `<div style="
                    width: 16px; height: 16px;
                    background: #c9a84c;
                    border-radius: 50%;
                    border: 3px solid rgba(201,168,76,0.4);
                    box-shadow: 0 0 12px rgba(201,168,76,0.8);
                "></div>`,
                iconSize:   [16, 16],
                iconAnchor: [8, 8],
            });

            L.marker([41.5842, 1.6010], { icon: goldIcon })
                .addTo(map)
                .bindPopup(`
                    <div style="font-family:Arial;font-size:12px;color:#333;text-align:center;padding:4px;">
                        <strong style="color:#c9a84c;">LEDBOYSS & LEDGIRLSS</strong><br/>
                        Institut Milà i Fontanals<br/>
                        Av. Emili Vallès, 4 · Igualada
                    </div>
                `)
                .openPopup();
        };
        document.head.appendChild(script);

        return () => {
            const mapEl = document.getElementById("mapa-ubicacion");
            if (mapEl && mapEl._leaflet_id) {
                window.L?.map(mapEl)?.remove?.();
            }
        };
    }, []);

    return <div id="mapa-ubicacion" style={{ width: "100%", height: "100%" }} />;
}

export default function Home() {
    const [formData, setFormData] = useState({ nombre: "", email: "", telefono: "", tipo: "", mensaje: "" });
    const [submitted, setSubmitted] = useState(false);

    const handleChange = (e) => setFormData({ ...formData, [e.target.name]: e.target.value });
    const handleSubmit = (e) => { e.preventDefault(); setSubmitted(true); };

    return (
        <div>
            {/* HERO */}
            <section className="hero">
                <video className="hero-video" autoPlay muted loop playsInline>
                    <source src="/videos/inicio_ledboys.mp4" type="video/mp4" />
                </video>
                <div className="hero-overlay" />
                <div className="hero-content">
                    <h1>LEDBOYSS <span>&</span> LEDGIRLSS</h1>
                    <p>Performance · Zancudos · Animación y Baile</p>
                    <a href="/catalogo" className="hero-btn">Ver Catálogo</a>
                </div>
                <div className="hero-scroll">
                    <span>Scroll</span>
                    <div className="hero-scroll-line" />
                </div>
            </section>

            {/* ABOUT */}
            <section className="about">
                <span className="section-label">Sobre nosotros</span>
                <div className="gold-divider" />
                <h2><span>LEDBOYSS & LEDGIRLSS</span> Performance –<br />Zancudos, Animación y Baile</h2>
                <p>Transformamos tus eventos en experiencias inolvidables. Con nuestra pasión por la música, danza y el entretenimiento, ofrecemos servicios de animación personalizados para discotecas, bodas y eventos de todo tipo.</p>
                <p>Nuestro equipo de profesionales se encarga de cada detalle, desde ofrecer la temática que más se adapte a tu fiesta, hasta la interacción con el público divertida, creando sonrisas e inolvidables momentos únicos.</p>
                <p>Déjanos ser parte de tu gran día o de tu fiesta, y juntos haremos que sea espectacular e inmejorable. ¡Contáctanos y descubre cómo podemos hacer de tu evento algo realmente diferente y especial!</p>
                <div className="about-cta" style={{ marginTop: "3rem" }}>
                    <a href="/catalogo" className="hero-btn">Ver catálogo</a>
                </div>
            </section>

            {/* CATEGORIES */}
            <section className="categories">
                <p className="categories-title">Nuestros servicios</p>
                <div className="categories-grid">
                    {CATEGORIES.map((cat) => (
                        <a key={cat.label} href={cat.href} className="category-card">
                            <img src={cat.img} alt={cat.label} />
                            <div className="category-card-overlay" />
                            <div className="category-card-content">
                                <h3>{cat.label}</h3>
                            </div>
                        </a>
                    ))}
                </div>
            </section>

            {/* DONDE HEMOS TRABAJADO */}
            <section className="partners">
                <span className="section-label">Presencia nacional</span>
                <div className="gold-divider" />
                <h2>DONDE HEMOS<br /><span>TRABAJADO</span></h2>
                <div className="partners-map">
                    <img src="/images/mapa_trabajo.png" alt="Mapa de trabajos realizados en España" />
                </div>
            </section>

            {/* DONDE NOS UBICAMOS */}
            <section className="ubicacion">
                <span className="section-label">Nuestra sede</span>
                <div className="gold-divider" />
                <h2>DONDE NOS<br /><span>UBICAMOS</span></h2>
                <p className="ubicacion-desc">Estamos en Igualada, Barcelona — listos para desplazarnos a cualquier punto de España.</p>
                <div className="ubicacion-mapa">
                    <MapaUbicacion />
                </div>
            </section>

            {/* CONTACT */}
            <section className="contact" id="contacto">
                <div className="contact-inner">
                    <span className="section-label">Contáctanos</span>
                    <div className="gold-divider" />
                    <h2>HAZ TU <span>EVENTO</span><br />ÚNICO</h2>
                    <p className="contact-subtitle">Cuéntanos qué necesitas y te preparamos una propuesta</p>
                    <a className="contact-phone" href="tel:+34637645824">📞 637 64 58 24</a>
                    {submitted ? (
                        <div className="form-success">✓ Mensaje enviado correctamente. Nos pondremos en contacto contigo pronto.</div>
                    ) : (
                        <form className="contact-form" onSubmit={handleSubmit}>
                            <div className="form-row">
                                <div className="form-group">
                                    <label>Nombre</label>
                                    <input type="text" name="nombre" value={formData.nombre} onChange={handleChange} placeholder="Tu nombre" required />
                                </div>
                                <div className="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" value={formData.email} onChange={handleChange} placeholder="tu@email.com" required />
                                </div>
                            </div>
                            <div className="form-row">
                                <div className="form-group">
                                    <label>Teléfono</label>
                                    <input type="tel" name="telefono" value={formData.telefono} onChange={handleChange} placeholder="666 000 000" />
                                </div>
                                <div className="form-group">
                                    <label>Tipo de evento</label>
                                    <select name="tipo" value={formData.tipo} onChange={handleChange}>
                                        <option value="">Selecciona...</option>
                                        <option value="boda">Boda</option>
                                        <option value="discoteca">Discoteca</option>
                                        <option value="evento">Evento</option>
                                        <option value="pasacalles">Pasacalles</option>
                                        <option value="otro">Otro</option>
                                    </select>
                                </div>
                            </div>
                            <div className="form-group">
                                <label>Mensaje</label>
                                <textarea name="mensaje" value={formData.mensaje} onChange={handleChange} placeholder="Cuéntanos sobre tu evento..." />
                            </div>
                            <div className="form-submit">
                                <button type="submit" className="btn-primary">Enviar</button>
                            </div>
                        </form>
                    )}
                </div>
            </section>

            <Footer />
        </div>
    );
}

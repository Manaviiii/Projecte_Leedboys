import React from "react";

export default function Footer() {
    return (
        <footer className="footer">
            <div className="footer-main">
                <div className="footer-brand">
                    <img src="/images/ledboyss_logo.png" alt="Ledboyss" style={{ height: 60, width: "auto" }} />
                    <p>
                        Transformamos tus eventos en experiencias inolvidables.
                        Animación, espectáculo y baile para todo tipo de eventos.
                    </p>
                </div>

                <div className="footer-links">
                    <h4>Navegación</h4>
                    <ul>
                        <li><a href="/">Inicio</a></li>
                        <li><a href="/catalogo">Catálogo</a></li>
                        <li><a href="/tipo/bodas">Bodas</a></li>
                        <li><a href="/tipo/discotecas">Discotecas</a></li>
                        <li><a href="/tipo/eventos">Eventos</a></li>
                        <li><a href="/tipo/eventos">Pasacalles</a></li>
                    </ul>
                </div>

                <div className="footer-social">
                    <h4>Síguenos</h4>
                    <div className="footer-social-links">
                        <a
                            className="footer-social-link"
                            href="https://www.instagram.com/ledboyss/?hl=es"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <div className="footer-social-icon">📸</div>
                            @Ledboyss
                        </a>
                        <a
                            className="footer-social-link"
                            href="https://www.instagram.com/ledgirlss/"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <div className="footer-social-icon">📸</div>
                            @Ledgirlss
                        </a>
                        <a className="footer-social-link" href="tel:+34644784285">
                            <div className="footer-social-icon">📞</div>
                            644 78 42 85
                        </a>
                    </div>
                </div>
            </div>

            <div className="footer-bottom">
                <p>Copyright LedBoyss</p>
                <div className="footer-bottom-links">
                    <a href="/politica-de-cookies">Política de cookies</a>
                    <a href="/politica-de-privacidad">Política de privacidad</a>
                </div>
            </div>
        </footer>
    );
}

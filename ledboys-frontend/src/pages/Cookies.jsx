import React from "react";
import Footer from "../components/Footer";
import "../styles/legal.less";

export default function Cookies() {
    return (
        <div className="legal-page">
            <div className="page-hero">
                <h1><span>POLÍTICA DE</span> COOKIES</h1>
            </div>
            <div className="legal-wrap">
                <p className="legal-updated">Última actualización: Mayo 2026</p>

                <div className="legal-section">
                    <h2>¿Qué son las cookies?</h2>
                    <p>Las cookies son pequeños archivos de texto que se almacenan en tu dispositivo cuando visitas nuestra web. Nos permiten recordar tus preferencias y mejorar tu experiencia de navegación.</p>
                </div>

                <div className="legal-section">
                    <h2>¿Qué cookies utilizamos?</h2>

                    <div className="legal-table">
                        <div className="legal-table-row legal-table-header">
                            <span>Cookie</span>
                            <span>Tipo</span>
                            <span>Finalidad</span>
                        </div>
                        <div className="legal-table-row">
                            <span>token</span>
                            <span>Necesaria</span>
                            <span>Mantiene la sesión del usuario autenticado</span>
                        </div>
                        <div className="legal-table-row">
                            <span>user</span>
                            <span>Necesaria</span>
                            <span>Almacena los datos básicos del usuario</span>
                        </div>
                        <div className="legal-table-row">
                            <span>carrito</span>
                            <span>Funcional</span>
                            <span>Guarda los items añadidos al carrito</span>
                        </div>
                    </div>
                </div>

                <div className="legal-section">
                    <h2>Cookies de terceros</h2>
                    <p>Utilizamos <strong>Stripe</strong> para el procesamiento de pagos. Stripe puede almacenar cookies propias necesarias para garantizar la seguridad de las transacciones. Para más información consulta la <a href="https://stripe.com/es/privacy" target="_blank" rel="noopener noreferrer">política de privacidad de Stripe</a>.</p>
                </div>

                <div className="legal-section">
                    <h2>¿Cómo desactivar las cookies?</h2>
                    <p>Puedes configurar tu navegador para rechazar cookies o eliminarlas. Ten en cuenta que desactivar las cookies necesarias puede afectar al funcionamiento de la web, especialmente el inicio de sesión y el carrito de compra.</p>
                </div>

                <div className="legal-section">
                    <h2>Contacto</h2>
                    <p>Si tienes dudas sobre nuestra política de cookies puedes contactarnos en <strong>info@ledboyss.com</strong> o llamarnos al <strong>637 64 58 24</strong>.</p>
                </div>
            </div>
            <Footer />
        </div>
    );
}

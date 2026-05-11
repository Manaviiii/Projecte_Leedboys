import React from "react";
import Footer from "../components/Footer";
import "../styles/legal.less";

export default function Privacidad() {
    return (
        <div className="legal-page">
            <div className="page-hero">
                <h1><span>POLÍTICA DE</span> PRIVACIDAD</h1>
            </div>
            <div className="legal-wrap">
                <p className="legal-updated">Última actualización: Mayo 2026</p>

                <div className="legal-section">
                    <h2>Responsable del tratamiento</h2>
                    <p><strong>LEDBOYSS & LEDGIRLSS Performance S.L.</strong><br />
                    CIF: B-87654321<br />
                    Dirección: Institut Milà i Fontanals, Igualada<br />
                    Email: info@ledboyss.com<br />
                    Teléfono: 637 64 58 24</p>
                </div>

                <div className="legal-section">
                    <h2>¿Qué datos recopilamos?</h2>
                    <p>Recopilamos los siguientes datos personales cuando utilizas nuestra web:</p>
                    <ul>
                        <li>Nombre y apellidos</li>
                        <li>Dirección de correo electrónico</li>
                        <li>Número de teléfono</li>
                        <li>DNI / NIF (para facturación)</li>
                        <li>Dirección postal y código postal</li>
                        <li>Datos de pago (gestionados de forma segura por Stripe)</li>
                    </ul>
                </div>

                <div className="legal-section">
                    <h2>¿Para qué usamos tus datos?</h2>
                    <ul>
                        <li>Gestionar tu cuenta y autenticación</li>
                        <li>Procesar tus pedidos y pagos</li>
                        <li>Emitir facturas y documentos fiscales</li>
                        <li>Contactarte para coordinar los detalles del evento</li>
                        <li>Enviarte información sobre tu reserva</li>
                    </ul>
                </div>

                <div className="legal-section">
                    <h2>Base legal del tratamiento</h2>
                    <p>El tratamiento de tus datos se basa en la ejecución del contrato de prestación de servicios que aceptas al realizar una compra, y en el cumplimiento de obligaciones legales en materia fiscal.</p>
                </div>

                <div className="legal-section">
                    <h2>¿Cuánto tiempo conservamos tus datos?</h2>
                    <p>Conservamos tus datos mientras mantengas tu cuenta activa. Los datos de facturación se conservan durante <strong>5 años</strong> por obligación fiscal. Puedes solicitar la eliminación de tu cuenta en cualquier momento.</p>
                </div>

                <div className="legal-section">
                    <h2>Tus derechos</h2>
                    <p>Tienes derecho a acceder, rectificar, suprimir, oponerte y solicitar la portabilidad de tus datos. Para ejercer tus derechos contacta con nosotros en <strong>info@ledboyss.com</strong>.</p>
                </div>

                <div className="legal-section">
                    <h2>Seguridad</h2>
                    <p>Los pagos son procesados por <strong>Stripe</strong>, que cumple con el estándar PCI DSS. No almacenamos datos de tarjetas bancarias en nuestros servidores.</p>
                </div>
            </div>
            <Footer />
        </div>
    );
}

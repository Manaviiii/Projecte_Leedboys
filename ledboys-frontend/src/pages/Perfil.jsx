import React, { useState, useEffect } from "react";
import "../styles/perfil.less";

export default function Perfil({ onLogout }) {
    const [user, setUser]       = useState(null);
    const [pagos, setPagos]     = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const token = localStorage.getItem("token");
        const headers = { "Authorization": `Bearer ${token}`, "Accept": "application/json" };

        Promise.all([
            fetch("/api/me",   { headers }).then(r => r.json()),
            fetch("/api/pagos", { headers }).then(r => r.json()),
        ]).then(([meData, pagosData]) => {
            setUser(meData.data || meData);
            const lista = pagosData.data || pagosData;
            setPagos(Array.isArray(lista) ? lista : []);
            setLoading(false);
        }).catch(() => setLoading(false));
    }, []);

    if (loading) return <div className="loading"><div className="loading-spinner" /></div>;
    if (!user)   return null;

    // Iniciales del avatar
    const iniciales = user.name
        .split(" ")
        .map(w => w[0])
        .slice(0, 2)
        .join("")
        .toUpperCase();

    // Estadísticas
    const totalPedidos = pagos.length;
    const totalGastado = pagos
        .filter(p => p.estado === "pagado")
        .reduce((sum, p) => sum + parseFloat(p.amount || 0), 0);
    const fechaRegistro = user.created_at
        ? new Date(user.created_at).toLocaleDateString("es-ES", { year: "numeric", month: "long", day: "numeric" })
        : "—";

    return (
        <div className="perfil-page">
            <div className="page-hero">
                <h1><span>MI</span> PERFIL</h1>
            </div>

            <div className="perfil-wrap">

                {/* TARJETA PRINCIPAL */}
                <div className="perfil-card">
                    <div className="perfil-avatar">
                        <span>{iniciales}</span>
                    </div>
                    <div className="perfil-info">
                        <h2 className="perfil-name">{user.name}</h2>
                        <p className="perfil-email">{user.email}</p>
                        <span className="perfil-role">{user.role || "cliente"}</span>
                    </div>
                    <div className="perfil-since">
                        <span className="perfil-since-label">Miembro desde</span>
                        <span className="perfil-since-date">{fechaRegistro}</span>
                    </div>
                </div>

                {/* ESTADÍSTICAS */}
                <div className="perfil-stats">
                    <div className="perfil-stat">
                        <span className="perfil-stat-num">{totalPedidos}</span>
                        <span className="perfil-stat-label">Pedidos</span>
                    </div>
                    <div className="perfil-stat-divider" />
                    <div className="perfil-stat">
                        <span className="perfil-stat-num">{totalGastado.toFixed(2)}€</span>
                        <span className="perfil-stat-label">Total gastado</span>
                    </div>
                    <div className="perfil-stat-divider" />
                    <div className="perfil-stat">
                        <span className="perfil-stat-num">
                            {pagos.filter(p => p.estado === "pagado").length}
                        </span>
                        <span className="perfil-stat-label">Confirmados</span>
                    </div>
                </div>

                {/* ACCESOS RÁPIDOS */}
                <div className="perfil-accesos">
                    <h3 className="perfil-section-title">Accesos rápidos</h3>
                    <div className="perfil-accesos-grid">
                        <a href="/facturas" className="perfil-acceso">
                            <span className="perfil-acceso-icon">🧾</span>
                            <div>
                                <span className="perfil-acceso-title">Facturas</span>
                                <span className="perfil-acceso-desc">Historial de pagos y descarga de PDFs</span>
                            </div>
                            <span className="perfil-acceso-arrow">→</span>
                        </a>
                        <a href="/reservas" className="perfil-acceso">
                            <span className="perfil-acceso-icon">📅</span>
                            <div>
                                <span className="perfil-acceso-title">Reservas</span>
                                <span className="perfil-acceso-desc">Tus eventos futuros contratados</span>
                            </div>
                            <span className="perfil-acceso-arrow">→</span>
                        </a>
                        <a href="/catalogo" className="perfil-acceso">
                            <span className="perfil-acceso-icon">✨</span>
                            <div>
                                <span className="perfil-acceso-title">Catálogo</span>
                                <span className="perfil-acceso-desc">Explora nuestros trajes LED</span>
                            </div>
                            <span className="perfil-acceso-arrow">→</span>
                        </a>
                    </div>
                </div>

                {/* CERRAR SESIÓN */}
                <div className="perfil-logout">
                    <button className="perfil-logout-btn" onClick={onLogout}>
                        Cerrar sesión
                    </button>
                </div>

            </div>
        </div>
    );
}

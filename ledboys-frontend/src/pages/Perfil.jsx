import React, { useState, useEffect } from "react";
import "../styles/perfil.less";

export default function Perfil({ onLogout }) {
    const [user, setUser]       = useState(null);
    const [pagos, setPagos]     = useState([]);
    const [loading, setLoading] = useState(true);

    const [nombre, setNombre]       = useState("");
    const [email, setEmail]         = useState("");
    const [emailPass, setEmailPass] = useState("");

    const [passwords, setPasswords] = useState({ actual: "", nueva: "", confirm: "" });

    const [msgNombre, setMsgNombre] = useState(null);
    const [msgEmail, setMsgEmail]   = useState(null);
    const [msgPass, setMsgPass]     = useState(null);

    const [savingNombre, setSavingNombre] = useState(false);
    const [savingEmail, setSavingEmail]   = useState(false);
    const [savingPass, setSavingPass]     = useState(false);

    useEffect(() => {
        const token   = localStorage.getItem("token");
        const headers = { "Authorization": `Bearer ${token}`, "Accept": "application/json" };

        Promise.all([
            fetch("/api/me",    { headers }).then(r => r.json()),
            fetch("/api/pagos", { headers }).then(r => r.json()),
        ]).then(([meData, pagosData]) => {
            const u = meData.data || meData;
            setUser(u);
            setNombre(u.name  || "");
            setEmail(u.email  || "");
            const lista = pagosData.data || pagosData;
            setPagos(Array.isArray(lista) ? lista : []);
            setLoading(false);
        }).catch(() => setLoading(false));
    }, []);

    const getHeaders = () => ({
        "Authorization":  `Bearer ${localStorage.getItem("token")}`,
        "Accept":         "application/json",
        "Content-Type":   "application/json",
    });

    const handleNombre = async (e) => {
        e.preventDefault();
        setSavingNombre(true);
        setMsgNombre(null);
        try {
            const res  = await fetch("/api/perfil", {
                method: "PUT",
                headers: getHeaders(),
                body: JSON.stringify({ name: nombre }),
            });
            const data = await res.json();
            if (!res.ok) { setMsgNombre({ error: true, text: data.message || "Error al guardar." }); return; }
            setUser(u => ({ ...u, name: nombre }));
            localStorage.setItem("user", JSON.stringify({ ...user, name: nombre }));
            setMsgNombre({ error: false, text: "Nombre actualizado." });
        } catch { setMsgNombre({ error: true, text: "Error de conexión." }); }
        finally  { setSavingNombre(false); }
    };

    const handleEmail = async (e) => {
        e.preventDefault();
        setSavingEmail(true);
        setMsgEmail(null);
        try {
            const res  = await fetch("/api/perfil/email", {
                method: "PUT",
                headers: getHeaders(),
                body: JSON.stringify({ email, password: emailPass }),
            });
            const data = await res.json();
            if (!res.ok) { setMsgEmail({ error: true, text: data.message || "Error al guardar." }); return; }
            setUser(u => ({ ...u, email }));
            localStorage.setItem("user", JSON.stringify({ ...user, email }));
            setEmailPass("");
            setMsgEmail({ error: false, text: "Email actualizado." });
        } catch { setMsgEmail({ error: true, text: "Error de conexión." }); }
        finally  { setSavingEmail(false); }
    };

    const handlePassword = async (e) => {
        e.preventDefault();
        if (passwords.nueva !== passwords.confirm) {
            setMsgPass({ error: true, text: "Las contraseñas no coinciden." });
            return;
        }
        setSavingPass(true);
        setMsgPass(null);
        try {
            const res  = await fetch("/api/perfil/password", {
                method: "PUT",
                headers: getHeaders(),
                body: JSON.stringify({
                    password_actual:      passwords.actual,
                    password:             passwords.nueva,
                    password_confirmation: passwords.confirm,
                }),
            });
            const data = await res.json();
            if (!res.ok) { setMsgPass({ error: true, text: data.message || "Error al guardar." }); return; }
            setPasswords({ actual: "", nueva: "", confirm: "" });
            setMsgPass({ error: false, text: "Contraseña actualizada." });
        } catch { setMsgPass({ error: true, text: "Error de conexión." }); }
        finally  { setSavingPass(false); }
    };

    if (loading) return <div className="loading"><div className="loading-spinner" /></div>;
    if (!user)   return null;

    const iniciales    = user.name.split(" ").map(w => w[0]).slice(0, 2).join("").toUpperCase();
    const totalPedidos = pagos.length;
    const totalGastado = pagos.filter(p => p.estado === "pagado").reduce((sum, p) => sum + parseFloat(p.amount || 0), 0);
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
                        <span className="perfil-stat-num">{pagos.filter(p => p.estado === "pagado").length}</span>
                        <span className="perfil-stat-label">Confirmados</span>
                    </div>
                </div>

                {/* EDITAR PERFIL */}
                <div className="perfil-editar">
                    <h3 className="perfil-section-title">Editar perfil</h3>

                    {/* Nombre */}
                    <div className="perfil-edit-block">
                        <h4 className="perfil-edit-label">Nombre</h4>
                        <form className="perfil-edit-form" onSubmit={handleNombre}>
                            <input
                                type="text"
                                value={nombre}
                                onChange={e => setNombre(e.target.value)}
                                placeholder="Tu nombre"
                                required
                            />
                            <button type="submit" disabled={savingNombre}>
                                {savingNombre ? "Guardando..." : "Guardar"}
                            </button>
                        </form>
                        {msgNombre && <p className={`perfil-edit-msg ${msgNombre.error ? "error" : "ok"}`}>{msgNombre.text}</p>}
                    </div>

                    {/* Email */}
                    <div className="perfil-edit-block">
                        <h4 className="perfil-edit-label">Email</h4>
                        <form className="perfil-edit-form perfil-edit-form--pass" onSubmit={handleEmail}>
                            <input
                                type="email"
                                value={email}
                                onChange={e => setEmail(e.target.value)}
                                placeholder="tu@email.com"
                                required
                            />
                            <input
                                type="password" onPaste={e => e.preventDefault()}
                                value={emailPass}
                                onChange={e => setEmailPass(e.target.value)}
                                placeholder="Contraseña actual para confirmar"
                                required
                            />
                            <button type="submit" disabled={savingEmail}>
                                {savingEmail ? "Guardando..." : "Guardar email"}
                            </button>
                        </form>
                        {msgEmail && <p className={`perfil-edit-msg ${msgEmail.error ? "error" : "ok"}`}>{msgEmail.text}</p>}
                    </div>

                    {/* Contraseña */}
                    <div className="perfil-edit-block">
                        <h4 className="perfil-edit-label">Contraseña</h4>
                        <form className="perfil-edit-form perfil-edit-form--pass" onSubmit={handlePassword}>
                            <input
                                type="password" onPaste={e => e.preventDefault()}
                                value={passwords.actual}
                                onChange={e => setPasswords({ ...passwords, actual: e.target.value })}
                                placeholder="Contraseña actual"
                                required
                            />
                            <input
                                type="password" onPaste={e => e.preventDefault()}
                                value={passwords.nueva}
                                onChange={e => setPasswords({ ...passwords, nueva: e.target.value })}
                                placeholder="Nueva contraseña"
                                required
                            />
                            <input
                                type="password" onPaste={e => e.preventDefault()}
                                value={passwords.confirm}
                                onChange={e => setPasswords({ ...passwords, confirm: e.target.value })}
                                placeholder="Confirmar nueva contraseña"
                                required
                            />
                            <button type="submit" disabled={savingPass}>
                                {savingPass ? "Guardando..." : "Cambiar contraseña"}
                            </button>
                        </form>
                        {msgPass && <p className={`perfil-edit-msg ${msgPass.error ? "error" : "ok"}`}>{msgPass.text}</p>}
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

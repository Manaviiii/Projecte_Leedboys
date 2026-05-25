import React, { useState } from "react";
import "../styles/registro.less";
import PasswordStrength from "../components/PasswordStrength";

export default function Registro() {
    const [form, setForm]       = useState({ name: "", email: "", password: "", password_confirmation: "" });
    const [error, setError]     = useState(null);
    const [loading, setLoading] = useState(false);

    const handleChange = (e) => setForm({ ...form, [e.target.name]: e.target.value });

    const handleSubmit = async (e) => {
        e.preventDefault();
        if (form.password !== form.password_confirmation) {
            setError("Las contraseñas no coinciden.");
            return;
        }
        setLoading(true);
        setError(null);

        try {
            const res  = await fetch("/api/registro", {
                method: "POST",
                headers: { "Content-Type": "application/json", "Accept": "application/json" },
                body: JSON.stringify(form),
            });
            const data = await res.json();

            if (!res.ok) {
                const msgs = data.errors ? Object.values(data.errors).flat().join(" ") : data.message;
                setError(msgs);
                return;
            }

            localStorage.setItem("token", data.token || data.data?.token);
            localStorage.setItem("user",  JSON.stringify(data.user || data.data?.user));
            window.history.pushState(null, "", "/");
            window.dispatchEvent(new PopStateEvent("popstate"));
        } catch {
            setError("Error al conectar con el servidor.");
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="registro-page">
            <div className="registro-bg">
                <div className="registro-glow registro-glow--1" />
                <div className="registro-glow registro-glow--2" />
                <div className="registro-glow registro-glow--3" />
            </div>

            <div className="registro-card">
                <div className="registro-logo">
                    <img src="/images/ledboyss_logo.png" alt="Ledboyss" />
                </div>

                <h1 className="registro-title">REGISTRO</h1>
                <div className="registro-divider" />
                <p className="registro-subtitle">Únete a LEDBOYSS & LEDGIRLSS</p>

                {error && <div className="registro-error">{error}</div>}

                <form className="registro-form" onSubmit={handleSubmit}>
                    <div className="registro-field">
                        <label>Nombre</label>
                        <input type="text" name="name" value={form.name} onChange={handleChange} placeholder="Tu nombre" required />
                    </div>
                    <div className="registro-field">
                        <label>Email</label>
                        <input type="email" name="email" value={form.email} onChange={handleChange} placeholder="tu@email.com" required />
                    </div>
                    <div className="registro-field">
                        <label>Contraseña</label>
                        <input type="password" name="password" value={form.password} onChange={handleChange} placeholder="Mínimo 8 caracteres" required />
                        <PasswordStrength password={form.password} />
                    </div>
                    <div className="registro-field">
                        <label>Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" value={form.password_confirmation} onChange={handleChange} placeholder="Repite tu contraseña" required />
                    </div>

                    <button type="submit" className="registro-btn" disabled={loading}>
                        {loading ? <span className="registro-spinner" /> : "CREAR CUENTA"}
                    </button>
                </form>

                <p className="registro-login">
                    ¿Ya tienes cuenta? <a href="/login">Inicia sesión</a>
                </p>
                <p className="registro-back">
                    <a href="/">← Volver al inicio</a>
                </p>
            </div>
        </div>
    );
}

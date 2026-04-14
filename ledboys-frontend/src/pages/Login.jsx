import React, { useState } from "react";
import "../styles/login.less";

export default function Login() {
    const [formData, setFormData] = useState({ email: "", password: "" });
    const [loading, setLoading]   = useState(false);
    const [error, setError]       = useState(null);

    const handleChange = (e) => setFormData({ ...formData, [e.target.name]: e.target.value });

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setError(null);

        try {
            const res  = await fetch("/api/login", {
                method: "POST",
                headers: { "Content-Type": "application/json", "Accept": "application/json" },
                body: JSON.stringify(formData),
            });

            const data = await res.json();

            if (!res.ok) {
                setError(data.message || "Credenciales incorrectas");
                setLoading(false);
                return;
            }

            // La API devuelve { success, message, data: { token, user } }
            const token = data.data?.token || data.token;
            const user  = data.data?.user  || data.user;

            localStorage.setItem("token", token);
            localStorage.setItem("user", JSON.stringify(user));

            window.history.pushState(null, "", "/");
            window.dispatchEvent(new PopStateEvent("popstate"));

        } catch {
            setError("Error de conexión con el servidor");
            setLoading(false);
        }
    };

    return (
        <div className="login-page">
            <div className="login-bg">
                <div className="login-bg-glow login-bg-glow--1" />
                <div className="login-bg-glow login-bg-glow--2" />
            </div>

            <div className="login-card">
                <div className="login-logo">
                    <img src="/images/ledboyss_logo.png" alt="Ledboyss" />
                </div>

                <h1 className="login-title">ACCEDER</h1>
                <div className="gold-divider" />
                <p className="login-subtitle">Inicia sesión para continuar</p>

                {error && <div className="login-error">{error}</div>}

                <form className="login-form" onSubmit={handleSubmit}>
                    <div className="form-group">
                        <label>Email</label>
                        <input
                            type="email"
                            name="email"
                            value={formData.email}
                            onChange={handleChange}
                            placeholder="tu@email.com"
                            required
                            autoComplete="email"
                        />
                    </div>
                    <div className="form-group">
                        <label>Contraseña</label>
                        <input
                            type="password"
                            name="password"
                            value={formData.password}
                            onChange={handleChange}
                            placeholder="••••••••"
                            required
                            autoComplete="current-password"
                        />
                    </div>
                    <button type="submit" className="login-btn" disabled={loading}>
                        {loading ? <span className="login-btn-spinner" /> : "ENTRAR"}
                    </button>
                </form>

                <p className="login-back">
                    <a href="/">← Volver al inicio</a>
                </p>
            </div>
        </div>
    );
}

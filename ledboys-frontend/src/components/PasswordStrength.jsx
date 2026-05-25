import React from "react";

export function getPasswordStrength(password) {
    if (!password) return null;

    let score = 0;
    if (/[a-z]/.test(password))        score++; // minúscula
    if (/[A-Z]/.test(password))        score++; // mayúscula
    if (/[0-9]/.test(password))        score++; // número
    if (/[^A-Za-z0-9]/.test(password)) score++; // símbolo
    if (password.length >= 8)           score++; // Igual o más de 8 caracteres

    if (score <= 1) return { label: "Débil",     level: 1, color: "#ff5050" };
    if (score <= 3) return { label: "Normal",    level: 2, color: "#ffaa00" };
    if (score === 4) return { label: "Fuerte",   level: 3, color: "#50c878" };
    return           { label: "Muy fuerte",      level: 4, color: "#00e5ff" };
}

export default function PasswordStrength({ password, className = "" }) {
    const strength = getPasswordStrength(password);
    if (!strength) return null;

    return (
        <div className={`pwd-strength ${className}`}>
            <div className="pwd-strength-bars">
                {[1, 2, 3, 4].map(i => (
                    <div
                        key={i}
                        className="pwd-strength-bar"
                        style={{
                            background: i <= strength.level ? strength.color : "rgba(255,255,255,0.08)",
                            transition: "background 0.3s",
                        }}
                    />
                ))}
            </div>
            <span className="pwd-strength-label" style={{ color: strength.color }}>
                {strength.label}
            </span>
        </div>
    );
}

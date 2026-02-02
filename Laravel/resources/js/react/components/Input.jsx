import React from 'react';
import '../../css/app.css'; // Para tener acceso a .input

export default function Input({ label, type = 'text', value, onChange }) {
    return (
        <div style={{ marginBottom: '12px', textAlign: 'left' }}>
            <label style={{ display: 'block', marginBottom: '4px', color: '#0ff', textShadow: '0 0 5px #0ff, 0 0 10px #0ff' }}>
                {label}
            </label>
            <input
                type={type}
                value={value}
                onChange={onChange}
                className="input"
            />
        </div>
    );
}

import React, { useState } from 'react';
import Input from '../components/Input';
import '../../css/app.css';

export default function Login() {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [error, setError] = useState('');

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const response = await fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ email, password }),
            });

            if (!response.ok) throw new Error('Credenciales incorrectas');

            window.location.href = '/dashboard';
        } catch (err) {
            setError(err.message);
        }
    };

    return (
        <div style={{ display:'flex', justifyContent:'center', alignItems:'center', height:'100vh' }}>
            <form className="form-container" onSubmit={handleSubmit}>
                <h1 className="text-neon" style={{ marginBottom: '20px' }}>Login</h1>
                
                {error && <p style={{ color: 'red', marginBottom: '12px' }}>{error}</p>}

                <Input label="Email" type="email" value={email} onChange={e => setEmail(e.target.value)} />
                <Input label="Contraseña" type="password" value={password} onChange={e => setPassword(e.target.value)} />

                <button type="submit" className="btn-neon" style={{ width: '100%' }}>
                    Entrar
                </button>
            </form>
        </div>
    );
}

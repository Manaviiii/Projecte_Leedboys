import React, { useState, useEffect } from "react";
import { useCart } from "../context/CartContext";
import "../styles/navbar.less";

export default function Navbar({ currentPath = "/", user = null, onLogout }) {
    const [scrolled, setScrolled] = useState(false);
    const [menuOpen, setMenuOpen] = useState(false);
    const { count, setOpen }      = useCart();

    useEffect(() => {
        const handleScroll = () => setScrolled(window.scrollY > 50);
        window.addEventListener("scroll", handleScroll);
        return () => window.removeEventListener("scroll", handleScroll);
    }, []);

    const handleAnchor = (e, href) => {
        if (href === "#contacto") {
            e.preventDefault();
            setMenuOpen(false);
            if (currentPath !== "/") {
                window.location.href = "/#contacto";
                return;
            }
            const el = document.getElementById("contacto");
            if (el) el.scrollIntoView({ behavior: "smooth" });
        } else {
            setMenuOpen(false);
        }
    };

    const links = [
        { label: "Inicio",     href: "/" },
        { label: "Catálogo",   href: "/catalogo" },
        { label: "Bodas",      href: "/tipo/bodas" },
        { label: "Discotecas", href: "/tipo/discotecas" },
        { label: "Eventos",    href: "/tipo/eventos" },
        { label: "Contacto",   href: "#contacto" },
    ];

    return (
        <>
            <nav className={`navbar${scrolled ? " scrolled" : ""}`}>
                <a className="navbar-logo-left" href="/tipo/ledboyss">
                    <img src="/images/ledboyss_logo.png" alt="Ledboyss Performance" />
                </a>

                <ul className="navbar-links">
                    {links.map((link) => (
                        <li key={link.label}>
                            <a
                                href={link.href}
                                className={currentPath === link.href ? "active" : ""}
                                onClick={(e) => handleAnchor(e, link.href)}
                            >
                                {link.label}
                            </a>
                        </li>
                    ))}
                </ul>

                <div className="navbar-actions">
                    {/* Icono carrito */}
                    <button className="navbar-cart-btn" onClick={() => setOpen(true)} aria-label="Carrito">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round">
                            <circle cx="9" cy="21" r="1"/>
                            <circle cx="20" cy="21" r="1"/>
                            <path d="M1 1h4l2.68 13.39a2 2 0 001.99 1.61h9.72a2 2 0 001.99-1.61L23 6H6"/>
                        </svg>
                        {count > 0 && <span className="navbar-cart-count">{count}</span>}
                    </button>

                    {/* Auth */}
                    {user ? (
                        <div className="navbar-user">
                            <span className="navbar-user-name">{user.name}</span>
                            <button className="navbar-logout" onClick={onLogout}>Salir</button>
                        </div>
                    ) : (
                        <a href="/login" className="navbar-login-btn">Iniciar sesión</a>
                    )}
                </div>

                <a className="navbar-logo-right" href="/tipo/ledgirlss">
                    <img src="/images/ledgirls_logo.png" alt="Ledgirlss Dancers" />
                </a>

                <button
                    className={`navbar-hamburger${menuOpen ? " open" : ""}`}
                    onClick={() => setMenuOpen(!menuOpen)}
                    aria-label="Menu"
                >
                    <span></span><span></span><span></span>
                </button>
            </nav>

            <div className={`navbar-mobile${menuOpen ? " open" : ""}`}>
                {links.map((link) => (
                    <a key={link.label} href={link.href} onClick={(e) => handleAnchor(e, link.href)}>
                        {link.label}
                    </a>
                ))}
                <button className="navbar-cart-btn" onClick={() => { setOpen(true); setMenuOpen(false); }}>
                    🛍️ Carrito {count > 0 && `(${count})`}
                </button>
                {user ? (
                    <button className="navbar-logout" onClick={onLogout}>Salir</button>
                ) : (
                    <a href="/login">Iniciar sesión</a>
                )}
            </div>
        </>
    );
}

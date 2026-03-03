import React, { useState, useEffect } from "react";

export default function Navbar({ currentPath = "/" }) {
    const [scrolled, setScrolled] = useState(false);
    const [menuOpen, setMenuOpen] = useState(false);

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

                {/* Logo Ledboyss */}
                <a className="navbar-logo-left" href="/tipo/ledboyss">
                    <img src="/images/ledboyss_logo.png" alt="Ledboyss Performance" />
                </a>

                {/* Nav links */}
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

                {/* Logo Ledgirlss */}
                <a className="navbar-logo-right" href="/tipo/ledgirlss">
                    <img src="/images/ledgirls_logo.png" alt="Ledgirlss Dancers" />
                </a>

                {/* Hamburger */}
                <button
                    className={`navbar-hamburger${menuOpen ? " open" : ""}`}
                    onClick={() => setMenuOpen(!menuOpen)}
                    aria-label="Menu"
                >
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </nav>

            {/* Mobile menu */}
            <div className={`navbar-mobile${menuOpen ? " open" : ""}`}>
                {links.map((link) => (
                    <a
                        key={link.label}
                        href={link.href}
                        onClick={(e) => handleAnchor(e, link.href)}
                    >
                        {link.label}
                    </a>
                ))}
            </div>
        </>
    );
}

import React from "react";
import Navbar from "./components/Navbar";
import Home from "./pages/Home";
import Catalogo from "./pages/Catalogo";
import TipoPage from "./pages/TipoPage";

function getRoute() {
    return window.location.pathname;
}

export default function Main() {
    const [path, setPath] = React.useState(getRoute());

    React.useEffect(() => {
        const handlePopState = () => setPath(getRoute());
        window.addEventListener("popstate", handlePopState);
        return () => window.removeEventListener("popstate", handlePopState);
    }, []);

    React.useEffect(() => {
        const handleClick = (e) => {
            const link = e.target.closest("a[href]");
            if (!link) return;
            const href = link.getAttribute("href");
            if (href && href.startsWith("/") && !href.startsWith("//")) {
                e.preventDefault();
                window.history.pushState(null, "", href);
                setPath(href);
                window.scrollTo(0, 0);
            }
        };
        document.addEventListener("click", handleClick);
        return () => document.removeEventListener("click", handleClick);
    }, []);

    const renderPage = () => {
        if (path === "/" || path === "") return <Home />;
        if (path === "/catalogo" || path.startsWith("/catalogo")) return <Catalogo />;
        if (path.startsWith("/tipo/")) {
            const tipo = path.replace("/tipo/", "").replace(/\/$/, "");
            return <TipoPage tipo={tipo} />;
        }
        return (
            <div style={{ display:"flex", flexDirection:"column", alignItems:"center", justifyContent:"center", minHeight:"100vh", gap:"1.5rem", textAlign:"center", padding:"2rem" }}>
                <h1 style={{ fontFamily:"var(--font-display)", fontSize:"8rem", letterSpacing:"8px", color:"var(--gold)" }}>404</h1>
                <p style={{ color:"rgba(255,255,255,0.5)", letterSpacing:"3px", textTransform:"uppercase" }}>Página no encontrada</p>
                <a href="/" className="hero-btn">Volver al inicio</a>
            </div>
        );
    };

    return (
        <>
            <Navbar currentPath={path} />
            <main>{renderPage()}</main>
        </>
    );
}

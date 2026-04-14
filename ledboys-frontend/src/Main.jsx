import React from "react";
import Navbar from "./components/Navbar";
import CartDrawer from "./components/CartDrawer";
import { CartProvider, useCart } from "./context/CartContext";
import Home from "./pages/Home";
import Catalogo from "./pages/Catalogo";
import TipoPage from "./pages/TipoPage";
import TrajeDetalle from "./pages/TrajeDetalle";
import Login from "./pages/Login";

function getRoute() {
    return window.location.pathname;
}

function parseUser() {
    const u = localStorage.getItem("user");
    return u && u !== "undefined" && u !== "null" ? JSON.parse(u) : null;
}

function App() {
    const [path, setPath] = React.useState(getRoute());
    const [user, setUser] = React.useState(parseUser);
    const { clearCart }   = useCart();

    React.useEffect(() => {
        const handlePopState = () => {
            setPath(getRoute());
            setUser(parseUser());
        };
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

    React.useEffect(() => {
        if (window.location.hash === "#contacto") {
            setTimeout(() => {
                const el = document.getElementById("contacto");
                if (el) el.scrollIntoView({ behavior: "smooth" });
            }, 100);
        }
    }, [path]);

    const handleLogout = () => {
        const token = localStorage.getItem("token");
        fetch("/api/logout", {
            method: "POST",
            headers: { "Authorization": `Bearer ${token}`, "Accept": "application/json" },
        }).finally(() => {
            localStorage.removeItem("token");
            localStorage.removeItem("user");
            clearCart();
            setUser(null);
            window.history.pushState(null, "", "/");
            setPath("/");
        });
    };

    const isLogin = path === "/login";

    const renderPage = () => {
        if (path === "/" || path === "") return <Home />;
        if (path === "/login") return <Login />;
        if (path === "/catalogo" || path.startsWith("/catalogo")) return <Catalogo />;
        if (path.startsWith("/tipo/")) {
            const tipo = path.replace("/tipo/", "").replace(/\/$/, "");
            return <TipoPage tipo={tipo} />;
        }
        if (path.startsWith("/traje/")) {
            const id = path.replace("/traje/", "").replace(/\/$/, "");
            return <TrajeDetalle id={id} />;
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
            {!isLogin && <Navbar currentPath={path} user={user} onLogout={handleLogout} />}
            <CartDrawer />
            <main>{renderPage()}</main>
        </>
    );
}

export default function Main() {
    return (
        <CartProvider>
            <App />
        </CartProvider>
    );
}

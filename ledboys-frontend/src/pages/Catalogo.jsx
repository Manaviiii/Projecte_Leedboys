import React, { useState, useEffect, useRef } from "react";
import Footer from "../components/Footer";
import "../styles/catalogo.less";

const API_URL    = "/api/trajes";
const SEARCH_URL = "/api/trajes/buscar";
const FILTERS    = ["Ledboys", "Ledgirls"];
const PER_PAGE   = 12;

export default function Catalogo() {
    const [allItems, setAllItems]         = useState([]);
    const [searchItems, setSearchItems]   = useState(null); // null = no hay búsqueda activa
    const [loading, setLoading]           = useState(true);
    const [searching, setSearching]       = useState(false);
    const [error, setError]               = useState(null);
    const [activeFilter, setActiveFilter] = useState("Todos");
    const [page, setPage]                 = useState(1);
    const [query, setQuery]               = useState("");
    const debounceRef                     = useRef(null);

    // Carga inicial
    useEffect(() => {
        fetch(API_URL)
            .then(res => {
                if (!res.ok) throw new Error("Error al cargar el catálogo");
                return res.json();
            })
            .then(data => {
                setAllItems(mapItems(data));
                setLoading(false);
            })
            .catch(err => { setError(err.message); setLoading(false); });
    }, []);

    // Búsqueda con debounce (espera 350ms tras dejar de escribir)
    useEffect(() => {
        if (debounceRef.current) clearTimeout(debounceRef.current);

        if (!query.trim()) {
            setSearchItems(null);
            return;
        }

        debounceRef.current = setTimeout(() => {
            setSearching(true);
            fetch(`${SEARCH_URL}?q=${encodeURIComponent(query.trim())}`)
                .then(res => res.json())
                .then(data => { setSearchItems(mapItems(data)); setSearching(false); })
                .catch(() => { setSearchItems([]); setSearching(false); });
        }, 350);

        return () => clearTimeout(debounceRef.current);
    }, [query]);

    function mapItems(data) {
        return data.map(item => ({
            id:     item.id,
            name:   item.nombre,
            img:    item.imagen ? `/${item.imagen}` : null,
            genero: item.traje?.genero ?? "unisex",
            precio: item.precio,
        }));
    }

    // Si hay búsqueda activa usamos esos resultados, si no el catálogo con filtro
    const baseItems = searchItems !== null ? searchItems : allItems;

    const filtered = activeFilter === "Todos" || searchItems !== null
        ? baseItems
        : baseItems.filter(item => {
            if (activeFilter === "Ledboys")  return item.genero === "chico"  || item.genero === "unisex";
            if (activeFilter === "Ledgirls") return item.genero === "chica"  || item.genero === "unisex";
            return true;
        });

    const totalPages = Math.ceil(filtered.length / PER_PAGE);
    const paginated  = filtered.slice((page - 1) * PER_PAGE, page * PER_PAGE);

    const handleFilter = (f) => { setActiveFilter(f); setPage(1); };
    const handleSearch = (e) => { setQuery(e.target.value); setPage(1); };
    const clearSearch  = () => { setQuery(""); setSearchItems(null); setPage(1); };

    useEffect(() => {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry, i) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => entry.target.classList.add("visible"), i * 60);
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.01, rootMargin: "200px" }
        );
        document.querySelectorAll(".catalog-item").forEach(el => observer.observe(el));
        return () => observer.disconnect();
    }, [paginated]);

    return (
        <div style={{ background: "#141414", minHeight: "100vh" }}>
            <div className="page-hero">
                <h1><span>CATÁ</span>LOGO</h1>
            </div>

            {/* BUSCADOR */}
            <div className="catalog-search-wrap">
                <div className="catalog-search">
                    <span className="catalog-search-icon">🔍</span>
                    <input
                        type="text"
                        placeholder="Buscar traje..."
                        value={query}
                        onChange={handleSearch}
                        className="catalog-search-input"
                    />
                    {query && (
                        <button className="catalog-search-clear" onClick={clearSearch}>✕</button>
                    )}
                </div>
            </div>

            {/* FILTROS — se ocultan si hay búsqueda activa */}
            {!query && (
                <div className="filter-tabs">
                    {FILTERS.map(f => (
                        <button
                            key={f}
                            className={`filter-tab${activeFilter === f ? " active" : ""}`}
                            onClick={() => handleFilter(f)}
                        >
                            {f}
                        </button>
                    ))}
                </div>
            )}

            <section className="catalog-section">
                {(loading || searching) && <div className="loading"><div className="loading-spinner" /></div>}
                {error && <p style={{ textAlign: "center", color: "#888", padding: "4rem" }}>{error}</p>}

                {!loading && !searching && !error && (
                    <>
                        {/* Resultado búsqueda */}
                        {searchItems !== null && (
                            <p className="catalog-search-result">
                                {searchItems.length} resultado{searchItems.length !== 1 ? "s" : ""} para <span>"{query}"</span>
                            </p>
                        )}

                        <div className="catalog-grid">
                            {paginated.map(item => (
                                <a key={item.id} href={`/traje/${item.id}`} className="catalog-item">
                                    {item.img
                                        ? <img src={item.img} alt={item.name} />
                                        : <div style={{ width:"100%", height:"100%", background:"#1a1a1a" }} />
                                    }
                                    <div className="catalog-item-overlay" />
                                    <div className="catalog-item-info">
                                        <h3>{item.name}</h3>
                                        <div className="tags">
                                            <span className="tag">{item.genero}</span>
                                        </div>
                                    </div>
                                </a>
                            ))}
                        </div>

                        {paginated.length === 0 && (
                            <p style={{ textAlign: "center", color: "#888", padding: "4rem", letterSpacing: "2px", textTransform: "uppercase", fontSize: "0.8rem" }}>
                                No se encontraron trajes
                            </p>
                        )}

                        {totalPages > 1 && (
                            <div className="pagination">
                                {page > 1 && <button onClick={() => setPage(p => p - 1)}>←</button>}
                                {Array.from({ length: totalPages }, (_, i) => i + 1).map(p => (
                                    <button key={p} className={p === page ? "active" : ""} onClick={() => setPage(p)}>{p}</button>
                                ))}
                                {page < totalPages && <button onClick={() => setPage(p => p + 1)}>→</button>}
                            </div>
                        )}
                    </>
                )}
            </section>

            <Footer />
        </div>
    );
}

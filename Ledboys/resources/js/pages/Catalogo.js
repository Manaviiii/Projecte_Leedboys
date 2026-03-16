import React, { useState, useEffect } from "react";
import Footer from "../components/Footer";

const ALL_ITEMS = [
    { id: 1,  name: "Daft Punk",         img: "/images/daft_punk.jpg",        tags: ["Discotecas", "Eventos"] },
    { id: 2,  name: "Iluminati",          img: "/images/iluminati.jpg",        tags: ["Eventos"] },
    { id: 3,  name: "Bad Bunny x Rauw",   img: "/images/Bad_bunny_x_rauw.jpg", tags: ["Discotecas", "Bodas"] },
    { id: 4,  name: "Mariachis",          img: "/images/mariachis.jpg",        tags: ["Bodas", "Eventos"] },
    { id: 5,  name: "Flower Power",       img: "/images/flower_power.jpg",     tags: ["Bodas", "Eventos"] },
    { id: 6,  name: "Árboles",            img: "/images/arboles.jpg",          tags: ["Eventos", "Pasacalles"] },
    { id: 7,  name: "Anubis",             img: "/images/anubis.jpg",           tags: ["Discotecas", "Eventos"] },
    { id: 8,  name: "Gladiadores",        img: "/images/gladiadores.jpg",      tags: ["Eventos"] },
    { id: 9,  name: "Motomamis",          img: "/images/motomamis.jpg",        tags: ["Discotecas"] },
    { id: 10, name: "Disco Girls",        img: "/images/disco_girls.jpg",      tags: ["Discotecas"] },
    { id: 11, name: "Ángeles",            img: "/images/angeles.jpg",          tags: ["Bodas"] },
    { id: 12, name: "Future Girls",       img: "/images/future_girls.jpg",     tags: ["Discotecas", "Eventos"] },
];

const FILTERS = ["Todos", "Discotecas", "Bodas", "Eventos", "Pasacalles"];
const PER_PAGE = 12;

export default function Catalogo() {
    const [activeFilter, setActiveFilter] = useState("Todos");
    const [page, setPage] = useState(1);

    const filtered = activeFilter === "Todos"
        ? ALL_ITEMS
        : ALL_ITEMS.filter(item => item.tags.includes(activeFilter));

    const totalPages = Math.ceil(filtered.length / PER_PAGE);
    const paginated = filtered.slice((page - 1) * PER_PAGE, page * PER_PAGE);

    const handleFilter = (f) => { setActiveFilter(f); setPage(1); };

    useEffect(() => {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry, i) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add("visible");
                        }, i * 60);
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

            <section className="catalog-section">
                <div className="catalog-grid">
                    {paginated.map(item => (
                        <div key={item.id} className="catalog-item">
                            <img src={item.img} alt={item.name} />
                            <div className="catalog-item-overlay" />
                            <div className="catalog-item-info">
                                <h3>{item.name}</h3>
                                <div className="tags">
                                    {item.tags.map(tag => (
                                        <span key={tag} className="tag">{tag}</span>
                                    ))}
                                </div>
                            </div>
                        </div>
                    ))}
                </div>

                {totalPages > 1 && (
                    <div className="pagination">
                        {page > 1 && <button onClick={() => setPage(p => p - 1)}>←</button>}
                        {Array.from({ length: totalPages }, (_, i) => i + 1).map(p => (
                            <button key={p} className={p === page ? "active" : ""} onClick={() => setPage(p)}>{p}</button>
                        ))}
                        {page < totalPages && <button onClick={() => setPage(p => p + 1)}>→</button>}
                    </div>
                )}
            </section>

            <Footer />
        </div>
    );
}

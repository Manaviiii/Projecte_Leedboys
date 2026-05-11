import React, { useState, useEffect } from "react";
import { useCart } from "../context/CartContext";
import "../styles/configurador.less";

export default function Configurador({ traje, stock, onClose }) {
    const { addItem, items }        = useCart();
    const [cantidad, setCantidad]   = useState(1);
    const [accesorios, setAccesorios] = useState([]);
    const [packs, setPacks]         = useState([]);
    const [selAccesorios, setSelAccesorios] = useState([]);
    const [selPacks, setSelPacks]   = useState([]);
    const [added, setAdded]         = useState(false);

    const img = traje.imagen ? `/${traje.imagen}` : null;

    // Cuántos de este traje ya hay en el carrito
    const enCarrito = items.find(i => i.id === `traje-${traje.id}`)?.cantidad ?? 0;
    const stockDisponible = stock - enCarrito;

    // Cargar accesorios y packs
    useEffect(() => {
        fetch("/api/accesorios")
            .then(r => r.json())
            .then(d => setAccesorios(d))
            .catch(() => {});

        fetch("/api/packs")
            .then(r => r.json())
            .then(d => setPacks(d))
            .catch(() => {});
    }, []);

    const toggleAccesorio = (id) => {
        setSelAccesorios(prev =>
            prev.includes(id) ? prev.filter(i => i !== id) : [...prev, id]
        );
    };

    const togglePack = (id) => {
        setSelPacks(prev =>
            prev.includes(id) ? prev.filter(i => i !== id) : [...prev, id]
        );
    };

    const totalTraje = traje.precio * cantidad;
    const totalAccesorios = accesorios
        .filter(a => selAccesorios.includes(a.id))
        .reduce((s, a) => s + parseFloat(a.precio), 0);
    const totalPacks = packs
        .filter(p => selPacks.includes(p.id))
        .reduce((s, p) => s + parseFloat(p.precio), 0);
    const total = totalTraje + totalAccesorios + totalPacks;

    const handleAddToCart = () => {
        addItem({
            id:       `traje-${traje.id}`,
            name:     traje.nombre,
            img,
            precio:   traje.precio,
            cantidad,
            tipo:     "Traje",
            stock,
        });

        accesorios
            .filter(a => selAccesorios.includes(a.id))
            .forEach(a => addItem({
                id:       `acc-${a.id}`,
                name:     a.nombre,
                img:      null,
                precio:   parseFloat(a.precio),
                cantidad: 1,
                tipo:     "Accesorio",
                stock:    999,
            }));

        packs
            .filter(p => selPacks.includes(p.id))
            .forEach(p => addItem({
                id:       `pack-${p.id}`,
                name:     p.nombre,
                img:      null,
                precio:   parseFloat(p.precio),
                cantidad: 1,
                tipo:     "Pack",
                stock:    999,
            }));

        setAdded(true);
        setTimeout(() => onClose(), 800);
    };

    return (
        <>
            <div className="config-overlay" onClick={onClose} />

            <div className="config-drawer">

                <div className="config-header">
                    <h2>CONFIGURAR PEDIDO</h2>
                    <button className="config-close" onClick={onClose}>✕</button>
                </div>

                <div className="config-body">

                    <div className="config-traje">
                        <div className="config-traje-img">
                            {img
                                ? <img src={img} alt={traje.nombre} />
                                : <div className="config-traje-placeholder" />
                            }
                        </div>
                        <div className="config-traje-info">
                            <h3>{traje.nombre}</h3>
                            <span>{traje.precio}€ / unidad</span>
                            <span className="config-stock">
                                Stock disponible: {stockDisponible} uds.
                                {enCarrito > 0 && ` (${enCarrito} en carrito)`}
                            </span>
                        </div>
                    </div>

                    <div className="config-section">
                        <h4>CANTIDAD</h4>
                        <div className="config-cantidad">
                            <button
                                onClick={() => setCantidad(c => Math.max(1, c - 1))}
                                disabled={cantidad <= 1}
                            >−</button>
                            <span>{cantidad}</span>
                            <button
                                onClick={() => setCantidad(c => Math.min(stockDisponible, c + 1))}
                                disabled={cantidad >= stockDisponible}
                            >+</button>
                        </div>
                        {cantidad >= stockDisponible && stockDisponible > 0 && (
                            <p style={{ color: "#ff6b6b", fontSize: "0.75rem", letterSpacing: "2px", marginTop: "0.5rem" }}>
                                Máximo disponible alcanzado
                            </p>
                        )}
                        {stockDisponible <= 0 && (
                            <p style={{ color: "#ff6b6b", fontSize: "0.75rem", letterSpacing: "2px", marginTop: "0.5rem" }}>
                                Ya tienes el máximo disponible en el carrito
                            </p>
                        )}
                    </div>

                    {accesorios.length > 0 && (
                        <div className="config-section">
                            <h4>ACCESORIOS EXTRA</h4>
                            <div className="config-extras">
                                {accesorios.map(a => (
                                    <div
                                        key={a.id}
                                        className={`config-extra-item${selAccesorios.includes(a.id) ? " selected" : ""}`}
                                        onClick={() => toggleAccesorio(a.id)}
                                    >
                                        <div className="config-extra-check">
                                            {selAccesorios.includes(a.id) && "✓"}
                                        </div>
                                        <div className="config-extra-info">
                                            <span>{a.nombre}</span>
                                            <span className="config-extra-price">+{a.precio}€</span>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {packs.length > 0 && (
                        <div className="config-section">
                            <h4>PACKS</h4>
                            <div className="config-extras">
                                {packs.map(p => (
                                    <div
                                        key={p.id}
                                        className={`config-extra-item${selPacks.includes(p.id) ? " selected" : ""}`}
                                        onClick={() => togglePack(p.id)}
                                    >
                                        <div className="config-extra-check">
                                            {selPacks.includes(p.id) && "✓"}
                                        </div>
                                        <div className="config-extra-info">
                                            <span>{p.nombre}</span>
                                            <span className="config-extra-price">+{p.precio}€</span>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}
                </div>

                <div className="config-footer">
                    <div className="config-total">
                        <span>Total selección</span>
                        <span className="config-total-price">{total.toFixed(2)}€</span>
                    </div>
                    <button
                        className={`config-add-btn${added ? " added" : ""}`}
                        onClick={handleAddToCart}
                        disabled={added || stockDisponible <= 0}
                    >
                        {added ? "✓ Añadido al carrito" : "Añadir al carrito"}
                    </button>
                </div>
            </div>
        </>
    );
}

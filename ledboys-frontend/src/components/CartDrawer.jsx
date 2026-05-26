import React, { useState, useEffect } from "react";
import { useCart } from "../context/CartContext";
import "../styles/cartdrawer.less";

function ImageLightbox({ src, alt, onClose }) {
    useEffect(() => {
        const handler = (e) => { if (e.key === "Escape") onClose(); };
        window.addEventListener("keydown", handler);
        return () => window.removeEventListener("keydown", handler);
    }, []);

    return (
        <div className="cart-lightbox" onClick={onClose}>
            <button className="cart-lightbox-close" onClick={onClose}>✕</button>
            <div className="cart-lightbox-img-wrap" onClick={e => e.stopPropagation()}>
                <img src={src} alt={alt} />
            </div>
        </div>
    );
}

const PACK_COLORS = {
    "Hora Loca Bronce":   "#cd7f32",
    "Hora Loca Plata":    "#c0c0c0",
    "Hora Loca Gold":     "#c9a84c",
    "Hora Loca Platinum": "#00e5ff",
};

export default function CartDrawer() {
    const { items, open, setOpen, removeItem, updateCantidad, total, count, packError } = useCart();
    const [lightbox, setLightbox] = useState(null);

    return (
        <>
            <div
                className={`cart-overlay${open ? " visible" : ""}`}
                onClick={() => setOpen(false)}
            />

            <div className={`cart-drawer${open ? " open" : ""}`}>

                <div className="cart-drawer-header">
                    <div className="cart-drawer-title">
                        <span>CARRITO</span>
                        {count > 0 && <span className="cart-drawer-count">{count}</span>}
                    </div>
                    <button className="cart-drawer-close" onClick={() => setOpen(false)}>✕</button>
                </div>

                {packError && (
                    <div className="cart-pack-error">
                        ⚠ {packError}
                    </div>
                )}

                <div className="cart-drawer-body">
                    {items.length === 0 ? (
                        <div className="cart-empty">
                            <p>Tu carrito está vacío</p>
                            <a href="/catalogo" className="hero-btn" onClick={() => setOpen(false)}>
                                Ver catálogo
                            </a>
                        </div>
                    ) : (
                        items.map(item => (
                            <div key={item.id} className="cart-item">
                                <div
                                    className={`cart-item-img${item.img ? " clickable" : ""}${item.tipo === "Pack" ? " cart-item-img--pack" : ""}`}
                                    onClick={() => item.img && setLightbox({ src: item.img, alt: item.name })}
                                >
                                    {item.img
                                        ? <img src={item.img} alt={item.name} />
                                        : item.tipo === "Pack"
                                            ? (
                                                <div className="cart-item-pack-placeholder">
                                                    <span style={{ color: PACK_COLORS[item.name] || "#c9a84c" }}>
                                                        {item.name.replace("Hora Loca ", "")}
                                                    </span>
                                                </div>
                                            )
                                            : <div className="cart-item-img-placeholder" />
                                    }
                                    {item.img && <div className="cart-item-img-zoom">🔍</div>}
                                </div>
                                <div className="cart-item-info">
                                    <h4>{item.name}</h4>
                                    <span className="cart-item-tipo">{item.tipo}</span>
                                    <div className="cart-item-controls">
                                        <button onClick={() => updateCantidad(item.id, item.cantidad - 1)}>−</button>
                                        <span>{item.cantidad}</span>
                                        <button
                                            onClick={() => updateCantidad(item.id, item.cantidad + 1)}
                                            disabled={item.stock && item.cantidad >= item.stock}
                                        >+</button>
                                    </div>
                                </div>
                                <div className="cart-item-right">
                                    <span className="cart-item-price">{(item.precio * item.cantidad).toFixed(2)}€</span>
                                    <button className="cart-item-remove" onClick={() => removeItem(item.id)}>✕</button>
                                </div>
                            </div>
                        ))
                    )}
                </div>

                {items.length > 0 && (
                    <div className="cart-drawer-footer">
                        <div className="cart-total">
                            <span>Total</span>
                            <span className="cart-total-price">{total.toFixed(2)}€</span>
                        </div>
                        <a
                            href={localStorage.getItem("token") ? "/checkout" : "/login"}
                            className="cart-checkout-btn"
                            onClick={() => setOpen(false)}
                        >
                            Pagar
                        </a>
                        <button className="cart-continue" onClick={() => setOpen(false)}>
                            Seguir comprando
                        </button>
                    </div>
                )}
            </div>

            {lightbox && (
                <ImageLightbox
                    src={lightbox.src}
                    alt={lightbox.alt}
                    onClose={() => setLightbox(null)}
                />
            )}
        </>
    );
}

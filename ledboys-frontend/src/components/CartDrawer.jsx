import React from "react";
import { useCart } from "../context/CartContext";
import "../styles/cartdrawer.less";

export default function CartDrawer() {
    const { items, open, setOpen, removeItem, updateCantidad, total, count } = useCart();

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
                                <div className="cart-item-img">
                                    {item.img
                                        ? <img src={item.img} alt={item.name} />
                                        : <div className="cart-item-img-placeholder" />
                                    }
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
        </>
    );
}

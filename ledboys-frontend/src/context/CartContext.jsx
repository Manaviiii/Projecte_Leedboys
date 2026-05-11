import React, { createContext, useContext, useState, useEffect } from "react";

const CartContext = createContext();

export function CartProvider({ children }) {
    const [items, setItems] = useState(() => {
        try {
            const saved = localStorage.getItem("carrito");
            return saved ? JSON.parse(saved) : [];
        } catch { return []; }
    });
    const [open, setOpen] = useState(false);

    // Guardar en localStorage cada vez que cambie
    useEffect(() => {
        localStorage.setItem("carrito", JSON.stringify(items));
    }, [items]);

    const addItem = (item) => {
        setItems(prev => {
            const exists = prev.find(i => i.id === item.id);
            if (exists) {
                return prev.map(i => i.id === item.id
                    ? { ...i, cantidad: i.cantidad + item.cantidad }
                    : i
                );
            }
            return [...prev, item];
        });
        setOpen(true); // abre el drawer al añadir
    };

    const removeItem = (id) => setItems(prev => prev.filter(i => i.id !== id));

    const updateCantidad = (id, cantidad) => {
        if (cantidad <= 0) { removeItem(id); return; }
        setItems(prev => prev.map(i => i.id === id ? { ...i, cantidad } : i));
    };

    const clearCart = () => setItems([]);

    const total = items.reduce((sum, i) => sum + (i.precio * i.cantidad), 0);
    const count  = items.reduce((sum, i) => sum + i.cantidad, 0);

    return (
        <CartContext.Provider value={{ items, open, setOpen, addItem, removeItem, updateCantidad, clearCart, total, count }}>
            {children}
        </CartContext.Provider>
    );
}

export function useCart() {
    return useContext(CartContext);
}

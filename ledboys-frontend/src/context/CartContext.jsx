import React, { createContext, useContext, useState, useEffect } from "react";

const CartContext = createContext();

const PACK_COLORS = {
    "Hora Loca Bronce":   "#cd7f32",
    "Hora Loca Plata":    "#c0c0c0",
    "Hora Loca Gold":     "#c9a84c",
    "Hora Loca Platinum": "#00e5ff",
};

export function CartProvider({ children }) {
    const [items, setItems] = useState(() => {
        try {
            const saved = localStorage.getItem("carrito");
            return saved ? JSON.parse(saved) : [];
        } catch { return []; }
    });
    const [open, setOpen]         = useState(false);
    const [packError, setPackError] = useState(null);

    useEffect(() => {
        localStorage.setItem("carrito", JSON.stringify(items));
    }, [items]);

    const addItem = (item) => {
        // Si es un pack, comprobar que no haya otro pack ya en el carrito
        if (item.tipo === "Pack") {
            const packExistente = items.find(i => i.tipo === "Pack" && i.id !== item.id);
            if (packExistente) {
                setPackError(`Ya tienes "${packExistente.name}" en el carrito. Solo puede haber un pack por compra.`);
                setOpen(true);
                setTimeout(() => setPackError(null), 4000);
                return;
            }
        }

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
        setOpen(true);
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
        <CartContext.Provider value={{ items, open, setOpen, addItem, removeItem, updateCantidad, clearCart, total, count, packError }}>
            {children}
        </CartContext.Provider>
    );
}

export function useCart() {
    return useContext(CartContext);
}

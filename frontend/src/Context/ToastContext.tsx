import {createContext, type ReactNode, useContext, useEffect, useState} from "react";

interface Toast {
    id: number;
    message: string;
    type: "success" | "error" | "info";
}

interface ToastContextValue {
    toasts: Toast[];
    showToast: (message: string, type?: Toast["type"]) => void;
}

const ToastContext = createContext<ToastContextValue | undefined>(undefined)

export const ToastProvider = ({ children }: { children: ReactNode }) => {
    const [toasts, setToasts] = useState<Toast[]>([]);

    useEffect(() => {
        const handler = (e: any)=> {
            showToast(e.detail.message, e.detail.type);
        }

        window.addEventListener("toast", handler)
        return () => window.removeEventListener("toast", handler)
    })

    const showToast = (message: string, type: Toast['type'] = "success")=> {
        const id = Date.now();

        setToasts((prev) => [...prev, { id, message, type}])

        setTimeout(() => {
            setToasts((prev) => prev.filter((t) => t.id !== id));
        }, 3000)
    }

    return (
        <ToastContext.Provider value={{ toasts, showToast }}>
            {children}
        </ToastContext.Provider>
    )
}

export const useToastContext = () => {
    const ctx = useContext(ToastContext)
    if (!ctx) {
        throw new Error("useToastContext must be used inside ToastProvider")
    }

    return ctx
}

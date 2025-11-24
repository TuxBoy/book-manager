import {useToastContext} from "../Context/ToastContext.tsx";

export const ToastContainer = () => {
    const { toasts } = useToastContext()

    return (
        <div className="toast toast-end z-50">
            {toasts.map(toast => (
                <div key={toast.id} className={`alert alert-${toast.type}`}>
                    <span>{toast.message}</span>
                </div>
            ))}
        </div>
    )
}

import {useToastContext} from "../Context/ToastContext.tsx";

export const useToast = () => {
    const { showToast } = useToastContext()

    return showToast;
}

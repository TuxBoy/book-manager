import {getToken, logout} from "../services/auth.ts";
import {Navigate, useNavigate} from "react-router-dom";
import {useToast} from "../hooks/useToast.ts";

export default function Logout() {
    const token = getToken()
    const toast = useToast()
    const navigate = useNavigate()

    if (token) {
        logout()
        toast('Vous êtes maintenant déconnecté !');
        navigate('/login')
        return (<p>Vous avez bien été déconnecté <Navigate to="/login" />Se connecter</p>)
    }

    return (<p></p>)
}

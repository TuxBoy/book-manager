import {getToken, logout} from "../services/auth.ts";
import {Navigate} from "react-router-dom";

export default function Logout() {
    const token = getToken()

    if (token) {
        logout()
        return <p>Vous avez bien été déconnecté <Navigate to="/login" /></p>
    }
}

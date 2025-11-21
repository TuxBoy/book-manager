import {useState} from "react";
import {getToken, login, type LoginResponse, logout} from "../services/auth.ts";

export function useAuth() {
    const [token, setToken] = useState<string | null>(getToken())
    const [loading, setLoading] = useState<boolean>(false)
    const [error, setError] = useState<string | null>(null)

    async function handleLogin(email: string, password: string): Promise<void> {
        setLoading(true)
        setError(null)

        try {
            const data: LoginResponse = await login(email, password)
            setToken(data.token)
        } catch (err: any) {
            setError(err)
        } finally {
            setLoading(false)
        }
    }

    function handleLogout(): void {
        logout()
        setToken(null)
    }

    return { token, loading, error, handleLogin, handleLogout }
}

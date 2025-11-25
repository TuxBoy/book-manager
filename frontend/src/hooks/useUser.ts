import {useEffect, useState} from "react";
import {apiFetch} from "../services/api.ts";
import {getToken} from "../services/auth.ts";

interface User {
    id: number;
    email: string;
    username: string;
}

export function useCurrentUser() {
    const [user, setUser] = useState<User | null>(null)
    const [loading, setLoading] = useState<boolean>(true)
    const [error, setError] = useState<string | null>(null)
    const token = getToken()

    useEffect(() => {
        if (!token) {
            return;
        }
        const fetchUser = async () => {
            try {
                const data = await apiFetch<User>('/api/users/me')
                setUser(data)
            } catch (err: any) {
                console.error(err)
                setError(err.message || "Impossible de récupérer l'utilisateur")
            } finally {
                setLoading(false)
            }
        }
        fetchUser()
    }, [])

    return { user, loading, error };
}

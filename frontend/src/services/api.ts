import {getToken} from "./auth.ts";

export async function apiFetch<T>(uri: string, options: RequestInit = {}): Promise<T> {
    const token = getToken()
    const url = `http://localhost:8000${uri}`

    const headers: HeadersInit = {
        "Content-Type": "application/json",
        ...(options.headers || {}),
        ...(token ? { Authorization: `Bearer ${token}` } : {}),
    }

    const response = await fetch(url, {
        ...options,
        headers
    })

    if (!response.ok) {
        throw new Error(`API error ${response.status}`)
    }

    return response.json() as Promise<T>
}

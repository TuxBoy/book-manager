import {getToken} from "./auth.ts";

export async function apiFetch<T>(url: string, options: RequestInit = {}): Promise<T> {
    const token = getToken()

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

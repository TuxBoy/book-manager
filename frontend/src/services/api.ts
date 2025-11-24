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

    if (response.status === 401) {
        const data = await response.json().catch(() => null)
        if (
            data?.message?.includes("Expired JWT") ||
            data?.detail?.includes("Expired JWT") ||
            data?.code === "401_expired"
        ) {
            // nettoyer le token
            localStorage.removeItem("token");

            // redirection login
            window.location.href = "/login";

            // toast
            const evt = new CustomEvent("toast", {
                detail: {
                    type: "error",
                    message: "Votre session a expiré. Veuillez vous reconnecter.",
                },
            });
            window.dispatchEvent(evt);

            throw new Error("JWT expired");
        }

        throw new Error(data?.detail || "Unauthorized");
    }


    if (!response.ok) {
        throw new Error(`API error ${response.status}`)
    }

    return response.json() as Promise<T>
}

import {BASE_URL} from "../config.ts";

export interface LoginResponse {
    token: string;
}

export async function login(email: string, password: string): Promise<LoginResponse> {
    const response = await fetch(BASE_URL + '/auth', {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ email , password })
    })

    if (!response.ok) {
        let error: any = {}
        try {
            error = await response.json()
        } catch {}

        throw new Error(error.detail ?? "Authentication failed")
    }

    const data: LoginResponse = await response.json()

    localStorage.setItem("jwt", data.token)

    return data
}

export function logout(): void {
    localStorage.removeItem('jwt')
}

export function getToken(): string | null {
    return localStorage.getItem('jwt')
}

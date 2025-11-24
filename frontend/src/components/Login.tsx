import React, { useState } from "react";
import {useAuth} from "../hooks/useAuth.ts";
import {useNavigate} from "react-router-dom";

export function Login() {
    const { handleLogin, loading, error, token } = useAuth();
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const navigate = useNavigate();

    const onSubmit = (e: React.FormEvent) => {
        e.preventDefault()
        handleLogin(email, password)
    }

    if (token) {
        return navigate('/')
    }

    return (
        <form onSubmit={onSubmit} className="max-w-md mx-auto space-y-4">
            <h2 className="text-2xl font-bold mb-4">Se connecter</h2>
            <input type="email" placeholder="Email" value={email} onChange={e => setEmail(e.target.value)} className="input input-bordered w-full" required />
            <input type="password" placeholder="Password" value={password} onChange={e => setPassword(e.target.value)} className="input input-bordered w-full" required />
            <button type="submit" disabled={loading} className="btn btn-primary w-full">
                {loading ? "Connexion..." : "Se connecter"}
            </button>
            {error && <p className="mt-2 text-red-400">{error}</p>}
        </form>
    );
}

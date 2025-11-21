import { useState } from "react";
import { useNavigate } from "react-router-dom";

export function Login() {
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [error, setError] = useState("");
    const navigate = useNavigate();

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        const res = await fetch("http://localhost:8000/api/login", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ email, password }),
        });
        const data = await res.json();
        if (res.ok && data.token) {
            localStorage.setItem("token", data.token);
            navigate("/books");
        } else {
            setError(data.message || "Erreur login");
        }
    };

    return (
        <form onSubmit={handleSubmit} className="max-w-md mx-auto space-y-4">
            <h2 className="text-2xl font-bold mb-4">Login</h2>
            <input type="email" placeholder="Email" value={email} onChange={e => setEmail(e.target.value)} className="input input-bordered w-full" required />
            <input type="password" placeholder="Password" value={password} onChange={e => setPassword(e.target.value)} className="input input-bordered w-full" required />
            <button type="submit" className="btn btn-primary w-full">Login</button>
            {error && <p className="mt-2 text-red-400">{error}</p>}
        </form>
    );
}

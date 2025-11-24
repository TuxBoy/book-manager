import { useState } from "react";

export function Register() {
    const [email, setEmail] = useState("");
    const [username, setUsername] = useState("");
    const [password, setPassword] = useState("");
    const [message, setMessage] = useState("");

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        const res = await fetch("http://localhost:8000/api/register", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ email, password, username }),
        });
        const data = await res.json();
        setMessage(data.message || JSON.stringify(data.errors));
    };

    return (
        <form onSubmit={handleSubmit} className="max-w-md mx-auto space-y-4">
            <h2 className="text-2xl font-bold mb-4">Register</h2>
            <input
                type="email"
                placeholder="Email"
                value={email}
                onChange={e => setEmail(e.target.value)} className="input input-bordered w-full" required
            />
            <input
                placeholder="Username"
                value={username}
                onChange={e => setUsername(e.target.value)} className="input input-bordered w-full"
            />
            <input
                type="password"
                placeholder="Password"
                value={password}
                onChange={e => setPassword(e.target.value)} className="input input-bordered w-full" required
            />
            <button type="submit" className="btn btn-primary w-full">Register</button>

            {message && <p className="mt-2 text-red-400">{message}</p>}
        </form>
    );
}

import { BrowserRouter, Routes, Route, Link } from "react-router-dom";
import {BookSearch} from "./components/BookSearch.tsx";
import {Register} from "./components/Register.tsx";
import {Login} from "./components/Login.tsx";

export default function App() {
    return (
        <div data-theme="dark" className="min-h-screen bg-gray-900 text-white">
            <BrowserRouter>
                <nav className="p-4 bg-gray-800 flex justify-between">
                    <div className="space-x-4">
                        <Link to="/register" className="btn btn-ghost">Register</Link>
                        <Link to="/login" className="btn btn-ghost">Login</Link>
                        <Link to="/books" className="btn btn-ghost">Books</Link>
                    </div>
                </nav>
                <div className="p-4">
                    <Routes>
                        <Route path="/register" element={<Register />} />
                        <Route path="/login" element={<Login />} />
                        <Route path="/books" element={<BookSearch />} />
                    </Routes>
                </div>
            </BrowserRouter>
        </div>
    );
}

import { BrowserRouter, Routes, Route, Link } from "react-router-dom";
import {BookSearch} from "./components/BookSearch.tsx";
import {Register} from "./components/Register.tsx";
import {Login} from "./components/Login.tsx";
import PrivateRoute from "./components/PrivateRoute.tsx";
import Logout from "./components/Logout.tsx";
import {getToken} from "./services/auth.ts";

export default function App() {
    const token = getToken()

    return (
        <div data-theme="dark" className="min-h-screen bg-gray-900 text-white">
            <BrowserRouter>
                <nav className="p-4 bg-gray-800 flex justify-between">
                    <div className="space-x-4">
                        <Link to="/register" className="btn btn-ghost">Register</Link>
                        <Link to="/login" className="btn btn-ghost">Login</Link>
                        {token && <Link to="/books" className="btn btn-ghost">Books</Link>}
                        {token && <Link to="/logout" className="btn btn-ghost">Se déconnecter</Link>}
                    </div>
                </nav>
                <div className="p-4">
                    <Routes>
                        <Route path="/register" element={<Register />} />
                        <Route path="/login" element={<Login />} />
                        <Route path="/books" element={
                            <PrivateRoute>
                                <BookSearch />
                            </PrivateRoute>
                        } />
                        <Route path="/logout" element={
                            <PrivateRoute>
                                <Logout />
                            </PrivateRoute>
                        } />
                    </Routes>
                </div>
            </BrowserRouter>
        </div>
    );
}

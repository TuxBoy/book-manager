import { BrowserRouter, Routes, Route, Link } from "react-router-dom";
import {BookSearch} from "./components/BookSearch.tsx";
import {Register} from "./components/Register.tsx";
import {Login} from "./components/Login.tsx";
import PrivateRoute from "./components/PrivateRoute.tsx";
import Logout from "./components/Logout.tsx";
import {getToken} from "./services/auth.ts";
import {BookTech} from "./components/BookTech.tsx";
import {ToastProvider} from "./Context/ToastContext.tsx";
import {ToastContainer} from "./components/ToastContainer.tsx";
import {useCurrentUser} from "./hooks/useUser.ts";

export default function App() {
    const token = getToken()
    const { user } = useCurrentUser()

    return (
        <div data-theme="dark" className="min-h-screen bg-gray-900 text-white">
            <ToastProvider>
                <BrowserRouter>
                    <nav className="p-4 bg-gray-800 flex justify-between">
                        <div className="space-x-4">
                            {!token && <Link to="/register" className="btn btn-ghost">Register</Link>}
                            {!token && <Link to="/login" className="btn btn-ghost">Login</Link>}
                            {token && <Link to="/books/search" className="btn btn-ghost">Rechercher un livre</Link>}
                            {token && <Link to="/books" className="btn btn-ghost">Ma BookTech({user?.username})</Link>}
                            {token && <Link to="/logout" className="btn btn-ghost">Se déconnecter</Link>}
                        </div>
                    </nav>
                    <div className="p-4">
                        <Routes>
                            <Route path="/register" element={<Register />} />
                            <Route path="/login" element={<Login />} />
                            <Route path="/books/search" element={
                                <PrivateRoute>
                                    <BookSearch />
                                </PrivateRoute>
                            } />
                            <Route path="/books" element={
                                <PrivateRoute>
                                    <BookTech />
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
                <ToastContainer />
            </ToastProvider>
        </div>
    );
}

import React, { useState } from "react";
import {apiFetch} from "../services/api.ts";

interface Book {
    title: string | null;
    authors: string[];
    description: string | null;
    image: string | null;
    isbn: string | null;
}

export const BookSearch: React.FC = () => {
    const [query, setQuery] = useState("");
    const [books, setBooks] = useState<Book[]>([]);
    const [loading, setLoading] = useState(false);

    const handleSearch = async () => {
        if (!query) return;
        setLoading(true);
        try {
            const data = await apiFetch<any>(`http://localhost:8000/api/books?q=${encodeURIComponent(query)}`);

            setBooks(data.member ?? []);
        } catch (err) {
            console.error(err);
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="min-h-screen bg-base-100 text-base-content p-6">
            <h1 className="text-3xl font-bold mb-6">Rechercher un livre</h1>

            <div className="flex gap-2 mb-6">
                <input
                    type="text"
                    placeholder="Enter book title or ISBN"
                    className="input input-bordered w-full"
                    value={query}
                    onChange={(e) => setQuery(e.target.value)}
                />
                <button
                    className="btn btn-primary"
                    onClick={handleSearch}
                    disabled={loading}
                >
                    {loading ? "Searching..." : "Search"}
                </button>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {books.map((book, idx) => (
                    <div key={idx} className="card bg-base-100 shadow-md">
                        {book.image && <figure><img src={book.image} alt={book.title ?? "Book"} /></figure>}
                        <div className="card-body">
                            <h2 className="card-title">{book.title}</h2>
                            <p className="text-sm text-gray-600">{book.authors.join(", ")}</p>
                            {book.description && <p className="text-sm mt-2 line-clamp-3">{book.description}</p>}
                            {book.isbn && <p className="text-xs mt-2">ISBN: {book.isbn}</p>}
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
};

import React, {useEffect, useState} from "react";
import {apiFetch} from "../services/api.ts";
import {useToast} from "../hooks/useToast.ts";

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
    const [adding, setAdding] = useState<Record<string, boolean>>({})
    const [userBooks, setUserBooks] = useState<Set<string>>(new Set())

    const toast = useToast()

    useEffect(() => {
        const fetchUserBooks = async () => {
            try {
                const data = await apiFetch<any>('/api/users/me/books')
                const books = data.member.map((b: any) => b.book) as Book[]
                const isbns = books.map(book => book.isbn).filter(Boolean) as string[]
                setUserBooks(new Set(isbns))
            } catch (err) {
                toast("Impossible de charger vos livres existants.", "error")
                console.error(err)
            }
        }
        fetchUserBooks()
    }, [])

    const handleSearch = async () => {
        if (!query) return;
        setLoading(true);
        try {
            const data = await apiFetch<any>(`/api/books?q=${encodeURIComponent(query)}`);

            setBooks(data.member ?? []);
        } catch (err) {
            console.error(err);
        } finally {
            setLoading(false);
        }
    };

    const handleAddBook = async (book: Book) => {
        if (!book.isbn) {
            return;
        }

        setAdding((prev) => ({...prev, [book.isbn!]: true}));

        try {
            await apiFetch(`/api/users/me/books`, {
                method: 'POST',
                body: JSON.stringify({
                    isbn: book.isbn,
                    title: book.title,
                    image: book.image,
                    description: book.description,
                    authors: book.authors
                }),
            })
            setUserBooks((prev) => new Set(prev).add(book.isbn!))
            toast(`Le livre "${book.title}" a été ajouté à votre BookTech !`);
        } catch (err) {
            console.error(err)
            toast("Erreur lors de l'ajout du livre. Vérifiez que vous êtes connecté.", "error");
        } finally {
            setAdding((prev) => ({ ...prev, [book.isbn!]: false }));
        }
    }

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
                {books.map((book, idx) => {
                    const isAdded = book.isbn ? userBooks.has(book.isbn) : false
                    return (
                        <div key={idx} className="card bg-base-100 shadow-md">
                            {book.image && <figure><img src={book.image} alt={book.title ?? "Book"} /></figure>}
                            <div className="card-body">
                                <h2 className="card-title">{book.title}</h2>
                                <p className="text-sm text-gray-600">{book.authors.join(", ")}</p>
                                {book.description && <p className="text-sm mt-2 line-clamp-3">{book.description}</p>}
                                {book.isbn && <p className="text-xs mt-2">ISBN: {book.isbn}</p>}

                                <button
                                    className={`btn mt-2 ${isAdded ? "btn-disabled" : "btn-secondary"}`}
                                    onClick={() => handleAddBook(book)}
                                    disabled={isAdded || adding[book.isbn ?? ""]}
                                >
                                    {isAdded
                                        ? "Déjà dans votre BookTech"
                                        : adding[book.isbn ?? ""] ? "Ajout en cours..." : "Ajouter à ma BookTech"
                                    }
                                    {isAdded && (
                                        <div className="badge badge-success mt-2">Ajouté</div>
                                    )}
                                </button>
                            </div>
                        </div>
                    )
                })}
            </div>
        </div>
    );
};

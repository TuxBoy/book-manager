import React, { useEffect, useState } from "react";
import { apiFetch } from "../services/api.ts";
import {useToast} from "../hooks/useToast.ts";

interface Book {
    id: number;
    title: string | null;
    authors: string[];
    description: string | null;
    isbn: string | null;
    image: string | null;
}

interface UserBook {
    id: number;
    book: Book;
    rating: number | null;
    comment: string | null;
    readingStatus: string;
}

interface UserBookCollection {
    member: UserBook[];
    totalItems: number;
}

export const BookTech: React.FC = () => {
    const [books, setBooks] = useState<UserBook[]>([]);
    const [loading, setLoading] = useState<boolean>(false);
    const [updating, setUpdating] = useState<{ [key: number]: boolean }>({});
    const toast = useToast()

    // states locaux pour conserver les saisies sans rerender global
    const [localComments, setLocalComments] = useState<{ [key: number]: string }>({});
    const [localRatings, setLocalRatings] = useState<{ [key: number]: number | null }>({});
    const [localStatuses, setLocalStatuses] = useState<{ [key: number]: string }>({});

    // Load the user bookTech
    const fetchBooks = async () => {
        setLoading(true);
        try {
            const data: UserBookCollection = await apiFetch<UserBookCollection>('/api/users/me/books');
            setBooks(data.member ?? []);

            // initialise les états locaux
            const comments: any = {};
            const ratings: any = {};
            const statuses: any = {};

            data.member.forEach((b: UserBook) => {
                comments[b.id] = b.comment ?? "";
                ratings[b.id] = b.rating ?? null;
                statuses[b.id] = b.readingStatus;
            });

            setLocalComments(comments);
            setLocalRatings(ratings);
            setLocalStatuses(statuses);

        } catch (err) {
            console.error(err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchBooks();
    }, []);

    const handleUpdate = async (
        userBookId: number,
        rating: number | null,
        comment: string | null,
        readingStatus: string
    ) => {
        setUpdating((prev) => ({ ...prev, [userBookId]: true }));

        try {
            await apiFetch(`/api/users/me/books/${userBookId}`, {
                method: "PATCH",
                body: JSON.stringify({ rating, comment, readingStatus }),
            });

            // mise à jour dans le tableau principal
            setBooks((prev) =>
                prev.map((b) =>
                    b.id === userBookId ? { ...b, rating, comment, readingStatus } : b
                )
            );

            toast('Livre mis à jour !')
        } catch (err) {
            console.error(err);
        } finally {
            setUpdating((prev) => ({ ...prev, [userBookId]: false }));
        }
    };

    const readingStatusOptions = [
        { value: 'to_read', label: "À lire" },
        { value: 'reading', label: "En cours" },
        { value: 'read', label: "Lu" },
    ];

    return (
        <div className="min-h-screen bg-base-100 text-base-content p-6">
            <h1 className="text-3xl font-bold mb-6">Ma BookTech</h1>

            {loading ? (
                <p>Chargement...</p>
            ) : (
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    {books.map((userBook) => (
                        <div key={userBook.id} className="card bg-base-100 shadow-md">
                            {userBook.book.image && (
                                <figure><img src={userBook.book.image} alt={userBook.book.title ?? "Book"} /></figure>
                            )}

                            <div className="card-body">
                                <h2 className="card-title">{userBook.book.title}</h2>
                                <p className="text-sm opacity-70">{userBook.book.authors.join(", ")}</p>

                                {userBook.book.description && (
                                    <p className="text-sm mt-2 line-clamp-3">{userBook.book.description}</p>
                                )}

                                <div className="mt-4 flex items-center gap-2">
                                    <label className="label"><span className="label-text">Note :</span></label>

                                    <input
                                        type="number"
                                        min={0}
                                        max={5}
                                        value={localRatings[userBook.id] ?? ""}
                                        className="input input-bordered input-sm w-20"
                                        onChange={(e) =>
                                            setLocalRatings((prev) => ({
                                                ...prev,
                                                [userBook.id]: e.target.value ? parseInt(e.target.value) : null
                                            }))
                                        }
                                        onBlur={() =>
                                            handleUpdate(
                                                userBook.id,
                                                localRatings[userBook.id],
                                                localComments[userBook.id],
                                                localStatuses[userBook.id]
                                            )
                                        }
                                        disabled={updating[userBook.id]}
                                    />
                                </div>

                                <div className="mt-2">
                                    <label className="label"><span className="label-text">Commentaire :</span></label>

                                    <textarea
                                        className="textarea textarea-bordered w-full"
                                        rows={3}
                                        value={localComments[userBook.id] ?? ""}
                                        onChange={(e) =>
                                            setLocalComments((prev) => ({
                                                ...prev,
                                                [userBook.id]: e.target.value
                                            }))
                                        }
                                        onBlur={() =>
                                            handleUpdate(
                                                userBook.id,
                                                localRatings[userBook.id],
                                                localComments[userBook.id],
                                                localStatuses[userBook.id]
                                            )
                                        }
                                        disabled={updating[userBook.id]}
                                    />
                                </div>

                                <div className="mt-2">
                                    <label className="label"><span className="label-text">Statut de lecture :</span></label>

                                    <select
                                        className="select select-bordered w-full"
                                        value={localStatuses[userBook.id] ?? userBook.readingStatus}
                                        onChange={(e) =>
                                            setLocalStatuses((prev) => ({
                                                ...prev,
                                                [userBook.id]: e.target.value
                                            }))
                                        }
                                        onBlur={() =>
                                            handleUpdate(
                                                userBook.id,
                                                localRatings[userBook.id],
                                                localComments[userBook.id],
                                                localStatuses[userBook.id]
                                            )
                                        }
                                        disabled={updating[userBook.id]}
                                    >
                                        {readingStatusOptions.map((opt) => (
                                            <option key={opt.value} value={opt.value}>{opt.label}</option>
                                        ))}
                                    </select>
                                </div>

                                {updating[userBook.id] && (
                                    <span className="text-sm opacity-60 mt-2">Mise à jour...</span>
                                )}
                            </div>
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
};

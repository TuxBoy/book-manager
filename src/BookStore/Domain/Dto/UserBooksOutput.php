<?php

declare(strict_types=1);

namespace App\BookStore\Domain\Dto;

use App\BookStore\Domain\Model\Book;
use App\BookStore\Domain\Model\UserBook;
use App\Security\Domain\Model\User;

final readonly class UserBooksOutput
{
    public function __construct(
        public User $user,
        public Book $book,
        public int $id,
        public ?int $rating = null,
        public ?string $comment = null,
    ) {
    }

    public static function fromEntity(UserBook $userBook): self
    {
        return new self(
            user: $userBook->user,
            book: $userBook->book,
            id: $userBook->id,
            rating: $userBook->rating,
            comment: $userBook->comment,
        );
    }
}

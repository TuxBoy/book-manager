<?php

declare(strict_types=1);

namespace App\BookStore\Application\Query;

use App\BookStore\Domain\Model\Book;
use App\BookStore\Infrastructure\Doctrine\Repository\BookRepository;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class FindBookQueryHandler
{
    public function __construct(private BookRepository $bookRepository)
    {
    }

    public function __invoke(FindBookQuery $query): ?Book
    {
        return $this->bookRepository->ofId($query->id);
    }
}

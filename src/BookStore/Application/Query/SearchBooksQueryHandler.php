<?php

declare(strict_types=1);

namespace App\BookStore\Application\Query;

use App\BookStore\Domain\Dto\SearchBook;
use App\BookStore\Domain\Port\Service\BookApiServiceInterface;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class SearchBooksQueryHandler
{
    public function __construct(private BookApiServiceInterface $bookApiService)
    {
    }

    /**
     * @return array<SearchBook>
     */
    public function __invoke(SearchBooksQuery $query): array
    {
        $items = $this->bookApiService->search($query->query);

        $books = [];

        foreach ($items as $item) {
            $info = $item['volumeInfo'];

            $book = new SearchBook();
            $book->title = $info['title'] ?? null;
            $book->authors = $info['authors'] ?? [];
            $book->description = $info['description'] ?? null;
            $book->image = $info['imageLinks']['thumbnail'] ?? null;

            foreach ($info['industryIdentifiers'] ?? [] as $id) {
                if ('ISBN_13' === $id['type']) {
                    $book->isbn = $id['identifier'];
                    break;
                }
            }

            $books[] = $book;
        }

        return $books;
    }
}

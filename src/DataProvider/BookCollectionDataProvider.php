<?php

declare(strict_types=1);

namespace App\DataProvider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\SearchBook;
use App\Service\BookApiService;

final readonly class BookCollectionDataProvider implements ProviderInterface
{
    public function __construct(private BookApiService $bookApiService)
    {
    }

    /**
     * @return array<SearchBook>
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $query = $context['filters']['q'] ?? null;

        if (null === $query) {
            return [];
        }

        $items = $this->bookApiService->search($query);

        $books = [];

        foreach ($items as $item) {
            $info = $item['volumeInfo'];
            $book = new SearchBook();
            $book->title = $info['title'] ?? null;
            $book->authors = $info['author'] ?? [];
            $book->description = $info['description'] ?? null;
            $book->image = $info['imageLinks']['thumbnail'] ?? null;

            foreach ($info['industryIdentifiers'] ?? [] as $id) {
                if ($id['type'] === 'ISBN_13') {
                    $book->isbn = $id['identifier'];
                    break;
                }
            }

            $books[] = $book;
        }

        return $books;
    }
}

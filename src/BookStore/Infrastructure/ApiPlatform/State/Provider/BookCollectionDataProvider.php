<?php

declare(strict_types=1);

namespace App\BookStore\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\BookStore\Application\Query\SearchBooksQuery;
use App\BookStore\Domain\Dto\SearchBook;
use App\Shared\Application\Query\QueryBusInterface;

final readonly class BookCollectionDataProvider implements ProviderInterface
{
    public function __construct(private QueryBusInterface $queryBus)
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

        return $this->queryBus->ask(new SearchBooksQuery($query));
    }
}

<?php

declare(strict_types=1);

namespace App\BookStore\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\BookStore\Application\Query\FindBookQuery;
use App\BookStore\Domain\Model\Book;
use App\Shared\Application\Query\QueryBusInterface;

/**
 * @implements ProviderInterface<Book>
 */
final readonly class BookItemProvider implements ProviderInterface
{
    public function __construct(private QueryBusInterface $queryBus)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?Book
    {
        /** @var int $id */
        $id = $uriVariables['id'];

        return $this->queryBus->ask(new FindBookQuery($id));
    }
}

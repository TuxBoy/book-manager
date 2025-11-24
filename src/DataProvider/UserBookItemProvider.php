<?php

declare(strict_types=1);

namespace App\DataProvider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\UserBook;
use App\Repository\UserBookRepository;

/**
 * @implements ProviderInterface<UserBook>
 */
final readonly class UserBookItemProvider implements ProviderInterface
{
    public function __construct(private UserBookRepository $repository)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?UserBook
    {
        $id = $uriVariables['id'] ?? null;

        return $this->repository->find($id);
    }
}

<?php

declare(strict_types=1);

namespace App\BookStore\Infrastructure\ApiPlatform\State\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\BookStore\Application\Query\FindUserBooksQuery;
use App\BookStore\Domain\Model\UserBook;
use App\Security\Domain\Model\User;
use App\Shared\Application\Query\QueryBusInterface;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<UserBook[]>
 */
final readonly class GetUserBooksCollectionProvider implements ProviderInterface
{
    public function __construct(
        private Security $security,
        private QueryBusInterface $queryBus,
    ) {
    }

    /**
     * @return array<UserBook>
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        /** @var User $user */
        $user = $this->security->getUser();

        return $this->queryBus->ask(new FindUserBooksQuery($user));
    }
}

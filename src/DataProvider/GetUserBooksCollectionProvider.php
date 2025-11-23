<?php

declare(strict_types=1);

namespace App\DataProvider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\UserBook;
use App\Repository\UserBookRepository;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<UserBook[]>
 */
final readonly class GetUserBooksCollectionProvider implements ProviderInterface
{
    public function __construct(private Security $security, private UserBookRepository $userBookRepository)
    {
    }

    /**
     * @return array<UserBook>
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $user = $this->security->getUser();

        return $this->userBookRepository->findBy(['user' => $user]);
    }
}

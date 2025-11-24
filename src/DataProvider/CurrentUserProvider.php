<?php

declare(strict_types=1);

namespace App\DataProvider;

use ApiPlatform\Metadata\Exception\AccessDeniedException;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\UserProfileOutput;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProviderInterface<UserProfileOutput>
 */
final readonly class CurrentUserProvider implements ProviderInterface
{
    public function __construct(private Security $security)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): UserProfileOutput
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new AccessDeniedException();
        }

        return UserProfileOutput::fromEntity($user);
    }
}

<?php

declare(strict_types=1);

namespace App\Security\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Security\Domain\Dto\RegisterUserInput;
use App\Security\Domain\Dto\UserProfileOutput;
use App\Security\Domain\Model\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @implements ProcessorInterface<RegisterUserInput, UserProfileOutput>
 */
#[AsController]
final readonly class UserRegistrationProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): UserProfileOutput
    {
        $user = new User();
        $user->setEmail($data->email);
        $user->setUsername($data->username);
        $user->setPassword($this->passwordHasher->hashPassword($user, $data->password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return UserProfileOutput::fromEntity($user);
    }
}

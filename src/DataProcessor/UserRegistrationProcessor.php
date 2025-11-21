<?php

declare(strict_types=1);

namespace App\DataProcessor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsController]
final readonly class UserRegistrationProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): User
    {
        $user = new User();
        $user->setEmail($data->email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $data->password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}


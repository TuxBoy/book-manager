<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\User;

final readonly class UserProfileOutput
{
    public function __construct(
        public int $id,
        public string $email,
        public string $username,
    ) {
    }

    public static function fromEntity(User $user): self
    {
        return new self(
            id: $user->getId(),
            email: $user->getEmail(),
            username: $user->getLogin(),
        );
    }
}

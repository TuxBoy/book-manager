<?php

declare(strict_types=1);

namespace App\BookStore\Domain\Dto;

use App\Security\Domain\Model\User;

final class UpdateUserBook
{
    public int $id;

    public User $user;

    public ?string $comment = null;

    public ?int $rating = null;

    public ?string $readingStatus = null;
}

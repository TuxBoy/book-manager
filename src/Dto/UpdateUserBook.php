<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\User;

final class UpdateUserBook
{
    public int $id;

    public User $user;

    public ?string $comment = null;

    public ?int $rating = null;

    public ?string $readingStatus = null;
}

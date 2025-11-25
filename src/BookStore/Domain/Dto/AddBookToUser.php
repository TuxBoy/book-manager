<?php

declare(strict_types=1);

namespace App\BookStore\Domain\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class AddBookToUser
{
    #[Assert\NotBlank]
    public ?string $isbn = null;

    #[Assert\NotBlank]
    public ?string $title = null;

    public ?array $authors = [];

    public ?string $image = null;

    public ?string $description = null;
}

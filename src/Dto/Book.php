<?php

declare(strict_types=1);

namespace App\Dto;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\DataProvider\BookCollectionDataProvider;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/books',
            provider: BookCollectionDataProvider::class,
        )
    ],
    normalizationContext: ['groups' => ['read']],
    paginationEnabled: false,
)]
final class Book
{
    #[Groups('read')]
    public ?string $title = null;

    #[Groups('read')]
    public ?array $authors = null;

    #[Groups('read')]
    public ?string $description = null;

    #[Groups('read')]
    public ?string $image = null;

    #[Groups('read')]
    public ?string $isbn = null;
}

<?php

declare(strict_types=1);

namespace App\BookStore\Domain\Dto;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\BookStore\Infrastructure\ApiPlatform\State\Provider\BookCollectionDataProvider;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    shortName: 'SearchBook',
    operations: [
        new GetCollection(
            uriTemplate: '/books/search',
            provider: BookCollectionDataProvider::class,
        ),
    ],
    normalizationContext: ['groups' => ['read']],
    paginationEnabled: false
)]
final class SearchBook
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

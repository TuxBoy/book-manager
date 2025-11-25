<?php

declare(strict_types=1);

namespace App\BookStore\Application\Query;

use App\BookStore\Domain\Dto\SearchBook;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<SearchBook>
 */
final readonly class SearchBooksQuery implements QueryInterface
{
    public function __construct(public string $query)
    {
    }
}

<?php

declare(strict_types=1);

namespace App\BookStore\Domain\Port\Service;

interface BookApiServiceInterface
{
    public function search(string $query): array;
}

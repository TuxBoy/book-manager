<?php

declare(strict_types=1);

namespace App\Service;

interface BookApiServiceInterface
{
    public function search(string $query): array;
}

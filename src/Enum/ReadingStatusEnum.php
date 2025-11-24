<?php

declare(strict_types=1);

namespace App\Enum;

enum ReadingStatusEnum: string
{
    case TO_READ = 'A lire';
    case READING = 'En cours';

    case READ = 'Lu';
}

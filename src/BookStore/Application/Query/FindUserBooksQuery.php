<?php

declare(strict_types=1);

namespace App\BookStore\Application\Query;

use App\BookStore\Domain\Model\UserBook;
use App\Security\Domain\Model\User;
use App\Shared\Application\Query\QueryInterface;

/**
 * @implements QueryInterface<UserBook[]>
 */
final readonly class FindUserBooksQuery implements QueryInterface
{
    public function __construct(public User $user)
    {
    }
}

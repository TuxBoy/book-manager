<?php

declare(strict_types=1);

namespace App\BookStore\Infrastructure\Factory;

use App\BookStore\Domain\Model\UserBook;
use App\Security\Domain\Model\User;
use App\Security\Infrastructure\Factory\UserFactory;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<User>
 */
final class UserBookFactory extends PersistentObjectFactory
{
    protected function defaults(): array|callable
    {
        return [
            'user' => UserFactory::new(),
            'book' => BookFactory::new(),
            'addedAt' => self::faker()->dateTimeBetween(startDate: '-1 year'),
        ];
    }

    public static function class(): string
    {
        return UserBook::class;
    }
}

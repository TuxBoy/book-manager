<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;
use App\Entity\UserBook;
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

<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<User>
 */
final class UserFactory extends PersistentObjectFactory
{
    protected function defaults(): array|callable
    {
        return [
            'email' => self::faker()->email(),
            'username' => self::faker()->username(),
            'password' => password_hash('password', PASSWORD_BCRYPT),
        ];
    }

    public static function class(): string
    {
        return User::class;
    }
}

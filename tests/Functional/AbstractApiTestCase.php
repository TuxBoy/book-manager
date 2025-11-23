<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use App\Factory\UserFactory;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

abstract class AbstractApiTestCase extends ApiTestCase
{
    use ResetDatabase;
    use Factories;

    protected static User $user;

    protected function setUp(): void
    {
        parent::setUp();

        static::$alwaysBootKernel = false;

        static::$user = UserFactory::createOne([
            'password' => password_hash('password', PASSWORD_BCRYPT),
        ]);
    }

    protected static function requestWithToken(string $method, string $url, array $options = []): ResponseInterface
    {
        $token = self::getContainer()
            ->get('lexik_jwt_authentication.jwt_manager')
            ->create(static::$user);

        $options += [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
                'Content-Type' => 'application/json',
            ],
        ];

        return static::createClient()->request($method, $url, $options);
    }
}

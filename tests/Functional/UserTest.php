<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Security\Infrastructure\Doctrine\Repository\UserRepository;

final class UserTest extends AbstractApiTestCase
{
    public function testGetCurrentUserInfos(): void
    {
        $response = static::requestWithToken('GET', '/api/users/me');

        $this->assertResponseIsSuccessful();

        $data = json_decode($response->getContent(), true);

        $this->assertEquals('doe@email.fr', $data['email']);
    }

    public function testRegistrationUser(): void
    {
        $response = static::createClient()->request('POST', '/api/register', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'username' => 'doe',
                'password' => 'password',
                'email' => 'john@email.fr',
            ],
        ]);

        $this->assertResponseIsSuccessful();

        /** @var UserRepository $userRepository */
        $userRepository = $this->getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => 'john@email.fr']);

        $this->assertNotNull($user);
        $this->assertSame('doe', $user->getLogin());
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Book;
use App\Entity\UserBook;
use App\Factory\UserFactory;
use App\Repository\BookRepository;
use App\Repository\UserBookRepository;
use PHPUnit\Framework\Attributes\DataProvider;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class BookTest extends AbstractApiTestCase
{
    use ResetDatabase, Factories;

    public function testAddBookToUserBookTech(): void
    {
        $payload = [
            'isbn' => '9782070368228',
            'title' => 'Test Book',
            'authors' => ['John Doe'],
            'description' => 'Book description',
            'image' => 'https://example.com/image.jpg',
        ];

        static::requestWithToken('POST', '/api/users/me/books', [
            'json' => $payload
        ]);

        $this->assertResponseIsSuccessful();

        $this->assertJsonContains([
            'book' => [
                'isbn' => '9782070368228',
                'title' => 'Test Book',
            ]
        ]);


        /** @var UserBookRepository $userBookRepository */
        $userBookRepository = $this->getContainer()->get('doctrine')->getRepository(UserBook::class);
        /** @var BookRepository $bookRepository */
        $bookRepository = $this->getContainer()->get('doctrine')->getRepository(Book::class);
        $userBook = $userBookRepository->findOneBy([
            'user' => static::$user,
            'book' => $bookRepository->findByIsbn('9782070368228'),
        ]);

        $this->assertNotNull($userBook);
        $this->assertEquals('to_read', $userBook->readingStatus);
    }

    #[DataProvider(methodName: 'provideBookData')]
    public function testFailData(array $payload): void
    {
        $user = UserFactory::createOne([
            'password' => password_hash('password', PASSWORD_BCRYPT),
        ]);

        $token = self::getContainer()->get('lexik_jwt_authentication.jwt_manager')
            ->create($user);

        $response = static::createClient()->request('POST', '/api/users/me/books', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ],
            'json' => $payload
        ]);

        $this->assertResponseStatusCodeSame(422);
    }

    public function testIfUserIsNotAuthorized(): void
    {
        $payload = [
            'isbn' => '9782070368228',
            'title' => 'Test Book',
            'authors' => ['John Doe'],
            'description' => 'Book description',
            'image' => 'https://example.com/image.jpg',
        ];

        static::createClient()->request('POST', '/api/users/me/books', [
            'headers' => [
                'Authorization' => 'Bearer fail',
                'Content-Type' => 'application/json',
            ],
            'json' => $payload
        ]);

        $this->assertResponseStatusCodeSame(401);
    }

    public static function provideBookData(): iterable
    {
        $data = static fn (array $data): array => $data + [
                'isbn' => '9782070368228',
                'title' => 'Test Book',
                'authors' => ['John Doe'],
                'description' => 'Book description',
                'image' => 'https://example.com/image.jpg',
         ];

        yield 'isbn is empty' => [$data(['isbn' => ''])];
        yield 'title is empty' => [$data(['title' => ''])];
    }
}

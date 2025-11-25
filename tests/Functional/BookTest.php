<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\BookStore\Domain\Model\Book;
use App\BookStore\Domain\Model\UserBook;
use App\BookStore\Infrastructure\Doctrine\Repository\BookRepository;
use App\BookStore\Infrastructure\Doctrine\Repository\UserBookRepository;
use App\BookStore\Infrastructure\Factory\UserBookFactory;
use App\Security\Infrastructure\Factory\UserFactory;
use PHPUnit\Framework\Attributes\DataProvider;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class BookTest extends AbstractApiTestCase
{
    use ResetDatabase;
    use Factories;

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
            'json' => $payload,
        ]);

        $this->assertResponseIsSuccessful();

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
                'Authorization' => 'Bearer '.$token,
                'Content-Type' => 'application/json',
            ],
            'json' => $payload,
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
            'json' => $payload,
        ]);

        $this->assertResponseStatusCodeSame(401);
    }

    public function testGetUserBooks(): void
    {
        UserBookFactory::createMany(number: 20);
        UserBookFactory::createMany(number: 10, attributes: ['user' => static::$user]);

        static::requestWithToken('GET', '/api/users/me/books');

        $this->assertResponseIsSuccessful();

        /** @var UserBookRepository $userBookRepository */
        $userBookRepository = $this->getContainer()->get('doctrine')->getRepository(UserBook::class);
        $userBooks = $userBookRepository->findBy(['user' => static::$user]);

        $this->assertCount(10, $userBooks);
    }

    public function testGetUserBooksIfEmpty(): void
    {
        static::requestWithToken('GET', '/api/users/me/books');

        $this->assertResponseIsSuccessful();

        /** @var UserBookRepository $userBookRepository */
        $userBookRepository = $this->getContainer()->get('doctrine')->getRepository(UserBook::class);
        $userBooks = $userBookRepository->findBy(['user' => static::$user]);

        $this->assertCount(0, $userBooks);
    }

    public function testUpdateUserBooksSuccessful(): void
    {
        /** @var UserBook $userBook */
        $userBook = UserBookFactory::createOne(['user' => static::$user, 'comment' => 'old comment']);

        static::requestWithToken('PATCH', '/api/users/me/books/'.$userBook->id, [
            'json' => [
                'comment' => 'Test comment',
                'rating' => 5,
                'readingStatus' => 'read',
            ],
        ]);

        $this->assertResponseIsSuccessful();

        /** @var UserBookRepository $userBookRepository */
        $userBookRepository = $this->getContainer()->get('doctrine')->getRepository(UserBook::class);
        $updated = $userBookRepository->find($userBook->id);

        $this->assertEquals('Test comment', $updated->comment);
    }

    public function testGetUserBooksFailIfNotAuthorized(): void
    {
        UserBookFactory::createMany(number: 10, attributes: ['user' => static::$user]);

        static::createClient()->request('GET', '/api/users/me/books', [
            'headers' => [
                'Authorization' => 'Bearer fail',
                'Content-Type' => 'application/json',
            ],
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

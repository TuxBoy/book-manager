<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Service\BookApiServiceInterface;
use App\Service\BookGoogleApiService;

final class SearchBookTest extends AbstractApiTestCase
{
    public function testSearchBook(): void
    {
        $client = static::createClient();

        $mockApiService = $this->createMock(BookApiServiceInterface::class);
        $mockApiService
            ->method('search')
            ->with('Harry Potter')
            ->willReturn([
                [
                    'volumeInfo' => [
                        'title' => 'Harry Potter and the Philosopher\'s Stone',
                        'authors' => ['J.K. Rowling'],
                        'description' => 'Magic book description',
                        'imageLinks' => ['thumbnail' => 'http://example.com/thumb.jpg'],
                        'industryIdentifiers' => [
                            ['type' => 'ISBN_13', 'identifier' => '9782070368228'],
                        ],
                    ],
                ],
            ]);

        $client->getContainer()->set(BookGoogleApiService::class, $mockApiService);

        $response = static::requestWithToken('GET', '/api/books', ['query' => ['q' => 'Harry Potter']]);

        $this->assertResponseIsSuccessful();

        $data = json_decode($response->getContent(), true);

        $this->assertIsArray($data);

        $this->assertCount(1, $data['member']);

        $book = $data['member'][0];

        $this->assertEquals('Harry Potter and the Philosopher\'s Stone', $book['title']);
        $this->assertEquals(['J.K. Rowling'], $book['authors']);
        $this->assertEquals('Magic book description', $book['description']);
        $this->assertEquals('http://example.com/thumb.jpg', $book['image']);
        $this->assertEquals('9782070368228', $book['isbn']);
    }
}

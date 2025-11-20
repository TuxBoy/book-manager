<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class BookApiService
{
    public function __construct(private HttpClientInterface $httpClient)
    {
    }

    public function search(string $query): array
    {
        $url = 'https://www.googleapis.com/books/v1/volumes';
        $response = $this->httpClient->request('GET', $url, [
            'query' => [
                'q' => $query,
                'maxResults' => 20,
            ]
        ]);

        $data = $response->toArray();
        if ([] === $data['items']) {
            return ['error' => 'Book not found'];
        }

        return $data['items'] ?? [];
    }
}

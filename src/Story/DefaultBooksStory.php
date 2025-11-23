<?php

declare(strict_types=1);

namespace App\Story;

use App\Factory\BookFactory;
use App\Factory\UserBookFactory;
use App\Factory\UserFactory;
use Zenstruck\Foundry\Story;

final class DefaultBooksStory extends Story
{
    public function build(): void
    {
        $users = UserFactory::createMany(number: 10);

        $books = BookFactory::createMany(number: 50);

        UserBookFactory::createMany(number: 150, attributes: static fn () => [
            'user' => $users[array_rand($users)],
            'book' => $books[array_rand($books)],
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Book;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Book>
 */
final class BookFactory extends PersistentObjectFactory
{
    #[\Override]
    public static function class(): string
    {
        return Book::class;
    }

    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'isbn' => self::faker()->unique()->isbn13(),
            'title' => self::faker()->title(),
            'authors' => [self::faker()->name()],
            'description' => self::faker()->paragraph(),
            'image' => self::faker()->imageUrl(width: 200, height: 300, category: 'books', randomize: true),
        ];
    }

    #[\Override]
    protected function initialize(): static
    {
        return $this;
    }
}

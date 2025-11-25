<?php

declare(strict_types=1);

namespace App\BookStore\Domain\Model;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\BookStore\Domain\Dto\AddBookToUser;
use App\BookStore\Domain\Dto\UpdateUserBook;
use App\BookStore\Infrastructure\ApiPlatform\State\Processor\AddBookToUserProcessor;
use App\BookStore\Infrastructure\ApiPlatform\State\Processor\UpdateUserBookProcessor;
use App\BookStore\Infrastructure\ApiPlatform\State\Provider\GetUserBooksCollectionProvider;
use App\BookStore\Infrastructure\ApiPlatform\State\Provider\UserBookItemProvider;
use App\BookStore\Infrastructure\Doctrine\Repository\UserBookRepository;
use App\Security\Domain\Model\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: UserBookRepository::class)]
#[Table(name: 'book_user')]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/users/me/books',
            security: "is_granted('ROLE_USER')",
            input: AddBookToUser::class,
            name: 'add_book',
            processor: AddBookToUserProcessor::class,
        ),
        new GetCollection(
            uriTemplate: '/users/me/books',
            security: "is_granted('ROLE_USER')",
            provider: GetUserBooksCollectionProvider::class,
        ),
        new Get(
            uriTemplate: '/users/books/{id}',
            security: "is_granted('ROLE_USER')",
            provider: UserBookItemProvider::class,
        ),
        new Patch(
            uriTemplate: '/users/me/books/{id}',
            inputFormats: ['json' => ['application/json'], 'merge-patch' => ['application/merge-patch+json']],
            security: "is_granted('ROLE_USER')",
            input: UpdateUserBook::class,
            name: 'update_book',
            provider: UserBookItemProvider::class,
            processor: UpdateUserBookProcessor::class,
        ),
    ]
)]
class UserBook
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    public ?int $id = null {
        get {
            return $this->id;
        }
        set {
            $this->id = $value;
        }
    }

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'userBooks')]
    #[ORM\JoinColumn(nullable: false)]
    public ?User $user = null {
        get {
            return $this->user;
        }
        set {
            $this->user = $value;
        }
    }

    #[ORM\ManyToOne(targetEntity: Book::class, cascade: ['persist'], inversedBy: 'userBooks')]
    #[ORM\JoinColumn(name: 'book_id', referencedColumnName: 'id', nullable: false)]
    public ?Book $book = null {
        get {
            return $this->book;
        }
        set {
            $this->book = $value;
        }
    }

    #[ORM\Column(options: ['default' => 'CURRENT_TIMESTAMP'])]
    public \DateTimeImmutable $addedAt {
        get {
            return $this->addedAt;
        }
        set {
            $this->addedAt = $value;
        }
    }

    #[ORM\Column(type: 'smallint', nullable: true)]
    public ?int $rating = null {
        get {
            return $this->rating;
        }
        set {
            $this->rating = $value;
        }
    }

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $comment = null {
        get {
            return $this->comment;
        }
        set {
            $this->comment = $value;
        }
    }

    #[ORM\Column(type: 'string', length: 20, options: ['default' => 'to_read'])]
    public string $readingStatus = 'to_read' {
        get {
            return $this->readingStatus;
        }
        set {
            $this->readingStatus = $value;
        }
    }

    public function __construct()
    {
        $this->addedAt = new \DateTimeImmutable();
    }
}

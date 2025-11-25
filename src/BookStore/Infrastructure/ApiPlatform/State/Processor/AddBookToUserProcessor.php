<?php

declare(strict_types=1);

namespace App\BookStore\Infrastructure\ApiPlatform\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\BookStore\Domain\Dto\AddBookToUser;
use App\BookStore\Domain\Model\Book;
use App\BookStore\Domain\Model\UserBook;
use App\BookStore\Infrastructure\Doctrine\Repository\BookRepository;
use App\BookStore\Infrastructure\Doctrine\Repository\UserBookRepository;
use App\Security\Domain\Model\User;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @implements ProcessorInterface<AddBookToUser, UserBook>
 */
final readonly class AddBookToUserProcessor implements ProcessorInterface
{
    public function __construct(
        private BookRepository $bookRepository,
        private UserBookRepository $userBookRepository,
        private Security $security,
    ) {
    }

    /**
     * @param AddBookToUser $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): UserBook
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $book = $this->bookRepository->findByIsbn($data->isbn);

        if (null === $book) {
            $book = new Book();
            $book->isbn = $data->isbn;
            $book->title = $data->title;
            $book->authors = $data->authors;
            $book->image = $data->image;
            $book->description = $data->description;

            $this->bookRepository->save($book);
        }

        $userBook = new UserBook();
        $userBook->book = $book;
        $userBook->user = $user;

        $this->userBookRepository->save($userBook);

        return $userBook;
    }
}

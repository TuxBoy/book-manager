<?php

declare(strict_types=1);

namespace App\DataProcessor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\AddBookToUser;
use App\Entity\Book;
use App\Entity\User;
use App\Entity\UserBook;
use App\Repository\BookRepository;
use App\Repository\UserBookRepository;
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

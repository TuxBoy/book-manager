<?php

declare(strict_types=1);

namespace App\BookStore\Application\Query;

use App\BookStore\Domain\Dto\UserBooksOutput;
use App\BookStore\Domain\Model\UserBook;
use App\BookStore\Infrastructure\Doctrine\Repository\UserBookRepository;
use App\Shared\Application\Query\AsQueryHandler;

#[AsQueryHandler]
final readonly class FindUserBooksQueryHandler
{
    public function __construct(private UserBookRepository $userBookRepository)
    {
    }

    /**
     * @return array<UserBooksOutput>
     */
    public function __invoke(FindUserBooksQuery $query): array
    {
        $userBooks = $this->userBookRepository->allOfUser($query->user);

        return array_map(static fn (UserBook $userBook) => UserBooksOutput::fromEntity($userBook), $userBooks);
    }
}

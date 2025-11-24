<?php

declare(strict_types=1);

namespace App\DataProcessor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\UpdateUserBook;
use App\Entity\UserBook;
use App\Repository\UserBookRepository;
use Webmozart\Assert\Assert;

/**
 * @implements ProcessorInterface<UpdateUserBook, UserBook>
 */
final readonly class UpdateUserBookProcessor implements ProcessorInterface
{
    public function __construct(private UserBookRepository $userBookRepository)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): UserBook
    {
        $previousData = $context['previous_data'] ?? null;

        Assert::isInstanceOf($previousData, UserBook::class);

        $userBook = $this->userBookRepository->findById($previousData->id);

        $userBook->comment = $data->comment;
        $userBook->rating = $data->rating;
        $userBook->readingStatus = $data->readingStatus;

        $this->userBookRepository->save($userBook);

        return $userBook;
    }
}

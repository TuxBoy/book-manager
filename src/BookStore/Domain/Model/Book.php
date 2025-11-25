<?php

declare(strict_types=1);

namespace App\BookStore\Domain\Model;

use App\BookStore\Infrastructure\Doctrine\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;

#[Entity(repositoryClass: BookRepository::class)]
class Book
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

    #[ORM\Column]
    public ?string $title = null {
        get {
            return $this->title;
        }
        set {
            $this->title = $value;
        }
    }

    #[ORM\Column(type: 'json', nullable: true)]
    public ?array $authors = null {
        get {
            return $this->authors;
        }
        set {
            $this->authors = $value;
        }
    }

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $description = null {
        get {
            return $this->description;
        }
        set {
            $this->description = $value;
        }
    }

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    public ?string $image = null {
        get {
            return $this->image;
        }
        set {
            $this->image = $value;
        }
    }

    #[ORM\Column(type: 'string', length: 20, unique: true, nullable: true)]
    public ?string $isbn = null {
        get {
            return $this->isbn;
        }
        set {
            $this->isbn = $value;
        }
    }

    #[ORM\OneToMany(targetEntity: UserBook::class, mappedBy: 'book', cascade: ['persist', 'remove'])]
    private Collection $userBooks {
        get {
            return $this->userBooks;
        }
    }

    public function __construct()
    {
        $this->userBooks = new ArrayCollection();
    }

    public function addUserBook(UserBook $userBook): self
    {
        if (!$this->userBooks->contains($userBook)) {
            $this->userBooks->add($userBook);
            $userBook->book = $this;
        }

        return $this;
    }
}

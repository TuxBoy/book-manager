<?php

declare(strict_types=1);

namespace App\Security\Domain\Model;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\BookStore\Domain\Model\UserBook;
use App\Security\Domain\Dto\RegisterUserInput;
use App\Security\Domain\Dto\UserProfileOutput;
use App\Security\Infrastructure\ApiPlatform\State\Processor\UserRegistrationProcessor;
use App\Security\Infrastructure\ApiPlatform\State\Provider\CurrentUserProvider;
use App\Security\Infrastructure\Doctrine\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/register',
            input: RegisterUserInput::class,
            processor: UserRegistrationProcessor::class,
        ),
        new Get(
            uriTemplate: '/users/me',
            output: UserProfileOutput::class,
            provider: CurrentUserProvider::class,
        ),
    ]
)]
#[ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 180)]
    private ?string $username = null;

    #[ORM\Column(type: 'string')]
    private ?string $password = null;

    #[ORM\OneToMany(targetEntity: UserBook::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private Collection $userBooks;

    public function __construct()
    {
        $this->userBooks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getUserIdentifier(): string
    {
        return $this->username ?? $this->email;
    }

    public function eraseCredentials(): void
    {
    }

    public function addUserBook(UserBook $userBook): self
    {
        if (!$this->userBooks->contains($userBook)) {
            $this->userBooks[] = $userBook;
            $userBook->user = $this;
        }

        return $this;
    }

    public function removeUserBook(UserBook $userBook): self
    {
        if ($this->userBooks->removeElement($userBook)) {
            if ($userBook->user === $this) {
                $userBook->user = null;
            }
        }

        return $this;
    }

    public function getLogin(): string
    {
        return $this->username;
    }

    public function getUsername(): ?string
    {
        return $this->email;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }
}

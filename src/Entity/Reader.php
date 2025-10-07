<?php

namespace App\Entity;

use App\Repository\ReaderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReaderRepository::class)]
class Reader
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    /**
     * @var Collection<int, Book>
     */
    #[ORM\ManyToMany(targetEntity: Book::class, inversedBy: 'readers')]
    private Collection $Read_book;

    public function __construct()
    {
        $this->Read_book = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getReadBook(): Collection
    {
        return $this->Read_book;
    }

    public function addReadBook(Book $readBook): static
    {
        if (!$this->Read_book->contains($readBook)) {
            $this->Read_book->add($readBook);
        }

        return $this;
    }

    public function removeReadBook(Book $readBook): static
    {
        $this->Read_book->removeElement($readBook);

        return $this;
    }
}

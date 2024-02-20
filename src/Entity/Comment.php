<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Assert\Length(min: 3, max: 30)]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    private ?string $name = null;


    #[ORM\Column(type: Types::TEXT)]
    #[Assert\length(min: 10, max: 300)]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    private ?string $comment = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero]
    #[Assert\NotNull()]
    #[Assert\NotBlank()]
    private ?int $rate = null;

    #[ORM\Column]
    #[Assert\NotNull()]
    private bool $isValid = false;

    public function __construct()
    {
        $this->isValid = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getIsValid(): ?bool
    {
        return $this->isValid;
    }

    public function setIsValid(bool $isValid): static
    {
        $this->isValid = $isValid;

        return $this;
    }
}

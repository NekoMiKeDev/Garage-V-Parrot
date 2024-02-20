<?php

namespace App\Entity;

use App\Repository\HoursRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HoursRepository::class)]
class Hours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank()]
    private ?string $day = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank()]
    private ?string $openingHours = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank()]
    private ?string $closingHours = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(string $day): static
    {
        $this->day = $day;

        return $this;
    }

    public function getOpeningHours(): ?string
    {
        return $this->openingHours;
    }

    public function setOpeningHours(string $openingHours): static
    {
        $this->openingHours = $openingHours;

        return $this;
    }

    public function getClosingHours(): ?string
    {
        return $this->closingHours;
    }

    public function setClosingHours(string $closingHours): static
    {
        $this->closingHours = $closingHours;

        return $this;
    }
}

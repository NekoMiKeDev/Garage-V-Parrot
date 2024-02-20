<?php

namespace App\Entity;

use App\Repository\RepairRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RepairRepository::class)]
class Repair
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;


    #[Assert\NotBlank()]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $repairType = null;


    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 30)]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $title = null;


    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 150)]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $repairDescription = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRepairType(): ?string
    {
        return $this->repairType;
    }

    public function setRepairType(string $repairType): static
    {
        $this->repairType = $repairType;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }


    public function getRepairDescription(): ?string
    {
        return $this->repairDescription;
    }

    public function setRepairDescription(string $repairDescription): static
    {
        $this->repairDescription = $repairDescription;

        return $this;
    }
}

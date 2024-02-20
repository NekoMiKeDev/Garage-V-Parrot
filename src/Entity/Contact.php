<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;


    #[Assert\NotBlank()]
    #[ORM\Column(type: Types::STRING)]
    #[Assert\Length(min: 2, max: 50)]
    private ?string $objet = null;


    #[Assert\NotBlank()]
    #[ORM\Column(type: Types::STRING)]
    #[Assert\Length(min: 2, max: 30)]
    private ?string $lastname = null;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 30)]
    #[ORM\Column(type: Types::STRING)]
    private ?string $firstname = null;

    #[Assert\NotBlank()]
    #[Assert\Email()]
    #[Assert\Length(min: 5, max: 100)]
    #[ORM\Column(type: Types::STRING)]
    private ?string $email = null;

    #[Assert\NotNull()]
    #[ORM\Column(type: Types::STRING)]
    #[Assert\Length(min: 10, max: 10)]
    private ?string $phoneNumber = null;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 20, max: 500)]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $contactDone = false;

    public function __construct()
    {
        $this->contactDone = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): static
    {
        $this->objet = $objet;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }


    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getContactDone(): ?bool
    {
        return $this->contactDone;
    }

    public function setContactDone(bool $contactDone): static
    {
        $this->contactDone = $contactDone;

        return $this;
    }
}

<?php

namespace App\Entity;
use Symfony\Component\HttpFoundation\File\File;
use App\Repository\CarImagesRepository;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


#[ORM\Entity(repositoryClass: CarImagesRepository::class)]
#[Vich\Uploadable]
class CarImages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\ManyToOne(inversedBy: 'carImages')]
    private ?Car $car = null;


    #[Vich\UploadableField(mapping: 'car', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;


    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setImageFile(?File  $imageFile = null): void
    {
        $this->imageFile = $imageFile;


        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
            
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }
    public function getImageName(): ?string
    {
        return $this->imageName;
    }
    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }
    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): static
    {
        $this->car = $car;

        return $this;
    }

}
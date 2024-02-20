<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CarRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Annotation\Groups;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: CarRepository::class)]
#[UniqueEntity('description')]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['car'])]
    private ?int $id = null;

    #[ORM\Column(type: "text")]
    #[Assert\Length(min: 2, max: 30)]
    #[Assert\NotBlank()]
    #[Groups(['car'])]
    private ?string $model = null;

    #[ORM\Column(type: "text")]
    #[Assert\Length(min: 20, max: 200)]
    #[Assert\NotBlank()]
    #[Groups(['car'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\Positive()]
    #[Assert\NotBlank()]
    #[Groups(['car'])]
    private ?float $price = null;

    #[Groups(['car'])]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateOfManufacture = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotNull()]
    #[Assert\Positive()]
    #[Groups(['car'])]
    private ?int $yearOfManufacture = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotNull()]
    #[Assert\Positive()]
    #[Groups(['car'])]
    private ?int $mileage = null;

    #[ORM\OneToMany(mappedBy: 'car', cascade: ["remove"], targetEntity: CarImages::class)]
    private Collection $carImages;

    #[ORM\OneToMany(mappedBy: 'carPdf', cascade: ["remove"], targetEntity: PdfStorage::class)]
    private Collection $pdfStorages;

    public function __construct()
    {
        $this->carImages = new ArrayCollection();
        $this->pdfStorages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDateOfManufacture(): ?\DateTimeInterface
    {
        return $this->dateOfManufacture;
    }

    public function setDateOfManufacture(\DateTimeInterface $dateOfManufacture): static
    {
        $this->dateOfManufacture = $dateOfManufacture;


        $this->yearOfManufacture = (int)$dateOfManufacture->format('Y');

        return $this;
    }

    public function getYearOfManufacture(): ?int
    {
        return $this->yearOfManufacture;
    }

    public function setYearOfManufacture(int $yearOfManufacture): static
    {
        $this->yearOfManufacture = $yearOfManufacture;

        return $this;
    }

    public function getMileage(): ?int
    {
        return $this->mileage;
    }

    public function setMileage(int $mileage): static
    {
        $this->mileage = $mileage;

        return $this;
    }

    /**
     * @return Collection<int, CarImages>
     */
    public function getCarImages(): Collection
    {
        return $this->carImages;
    }

    public function addCarImage(CarImages $carImage): static
    {
        if (!$this->carImages->contains($carImage)) {
            $this->carImages[] = $carImage;
            $carImage->setCar($this);
        }

        return $this;
    }

    public function removeCarImage(CarImages $carImage): static
    {
        if ($this->carImages->removeElement($carImage)) {
            // set the owning side to null (unless already changed)
            if ($carImage->getCar() === $this) {
                $carImage->setCar(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PdfStorage>
     */
    public function getPdfStorages(): Collection
    {
        return $this->pdfStorages;
    }

    public function addPdfStorage(PdfStorage $pdfStorage): static
    {
        // Supprimez tous les PDF associés à la voiture
        $this->pdfStorages->clear();
    
        // Ajoutez le nouveau PDF
        $this->pdfStorages->add($pdfStorage);
        $pdfStorage->setCarPdf($this);
    
        return $this;
    }
    
    public function removePdfStorage(PdfStorage $pdfStorage): static
    {
        if ($this->pdfStorages->removeElement($pdfStorage)) {
            // set the owning side to null (unless already changed)
            if ($pdfStorage->getCarPdf() === $this) {
                $pdfStorage->setCarPdf(null);
            }
        }

        return $this;
    }
}

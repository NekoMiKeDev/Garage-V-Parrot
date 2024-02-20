<?php

namespace App\Entity;

use App\Repository\PdfStorageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PdfStorageRepository::class)]
class PdfStorage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $fileName = null;

    #[ORM\Column(length: 255)]
    private ?string $fileType = null;


    #[ORM\Column]
    private ?\DateTimeImmutable $uploadDate;

    #[ORM\ManyToOne(inversedBy: 'pdfStorages')]
    private ?Car $carPdf = null;


    public function __construct()
    {
        $this->uploadDate = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getFileType(): ?string
    {
        return $this->fileType;
    }

    public function setFileType(string $fileType): static
    {
        $this->fileType = $fileType;

        return $this;
    }

    public function getUploadDate(): ?\DateTimeImmutable
    {
        return $this->uploadDate;
    }

    public function setUploadDate(\DateTimeImmutable $uploadDate): static
    {
        $this->uploadDate = $uploadDate;

        return $this;
    }

    public function getCarPdf(): ?Car
    {
        return $this->carPdf;
    }

    public function setCarPdf(?Car $carPdf): static
    {
        $this->carPdf = $carPdf;

        return $this;
    }
}

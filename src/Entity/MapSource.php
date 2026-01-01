<?php

namespace BikeCoders\MapBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'map_source')]
class MapSource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $region = null;

    #[ORM\Column(length: 255)]
    private ?string $version = null; // e.g. "2024-01-01"

    #[ORM\Column(length: 255)]
    private ?string $s3Key = null;

    #[ORM\Column(length: 255)]
    private ?string $sourceUrl = null;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $minZoom = 0;

    #[ORM\Column(type: 'integer', options: ['default' => 15])]
    private int $maxZoom = 15;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $bounds = null; // [minLon, minLat, maxLon, maxLat]

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isActive = false;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): static
    {
        $this->version = $version;

        return $this;
    }

    public function getS3Key(): ?string
    {
        return $this->s3Key;
    }

    public function setS3Key(string $s3Key): static
    {
        $this->s3Key = $s3Key;

        return $this;
    }

    public function getSourceUrl(): ?string
    {
        return $this->sourceUrl;
    }

    public function setSourceUrl(string $sourceUrl): static
    {
        $this->sourceUrl = $sourceUrl;

        return $this;
    }

    public function getMinZoom(): int
    {
        return $this->minZoom;
    }

    public function setMinZoom(int $minZoom): static
    {
        $this->minZoom = $minZoom;

        return $this;
    }

    public function getMaxZoom(): int
    {
        return $this->maxZoom;
    }

    public function setMaxZoom(int $maxZoom): static
    {
        $this->maxZoom = $maxZoom;

        return $this;
    }

    public function getBounds(): ?array
    {
        return $this->bounds;
    }

    public function setBounds(?array $bounds): static
    {
        $this->bounds = $bounds;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\ShortLinkRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShortLinkRepository::class)]
class ShortLink
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $addedAt = null;

    #[ORM\Column(length: 6, unique: true)]
    private ?string $hashId = null;

    #[ORM\Column(length: 255)]
    private ?string $origin = null;


    public function __construct(string $origin, string $hashId)
    {
        $this->setOrigin($origin);
        $this->addedAt = new \DateTime();
        $this->hashId = $hashId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddedAt(): ?\DateTimeInterface
    {
        return $this->addedAt;
    }

    public function setAddedAt(\DateTimeInterface $addedAt): static
    {
        $this->addedAt = $addedAt;

        return $this;
    }

    public function getHashId(): ?string
    {
        return $this->hashId;
    }

    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function setOrigin(string $origin): static
    {
        $this->origin = $origin;

        return $this;
    }

    private function generateHashId() : void
    {
        $this->hashId = substr(hash('sha256',rand(10000, 100000)), -6);
    }
}

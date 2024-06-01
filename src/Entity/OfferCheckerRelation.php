<?php

namespace App\Entity;

use App\Repository\OfferCheckerRelationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OfferCheckerRelationRepository::class)]
class OfferCheckerRelation
{
    const CHECKER_LEAD_GID = 'lead_gid';
    const CHECKER_LEAD_CRAFT = 'lead_craft';
    const CHECKER_LEAD_SU = 'lead_su';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $externalOfferId = null;

    #[ORM\Column(length: 50)]
    private ?string $checker = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $addedAt = null;

    #[ORM\ManyToOne(inversedBy: 'offerCheckerRelations')]
    private ?Offer $offer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExternalOfferId(): ?int
    {
        return $this->externalOfferId;
    }

    public function setExternalOfferId(int $externalOfferId): static
    {
        $this->externalOfferId = $externalOfferId;

        return $this;
    }

    public function getChecker(): ?string
    {
        return $this->checker;
    }

    public function setChecker(string $checker): static
    {
        $this->checker = $checker;

        return $this;
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

    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    public function setOffer(?Offer $offer): static
    {
        $this->offer = $offer;

        return $this;
    }
}

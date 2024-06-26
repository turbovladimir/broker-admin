<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OfferRepository::class)]
class Offer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $addedAt = null;

    #[ORM\Column(nullable: 0, options: ["default" => 0])]
    private bool $isActive = false;

    #[ORM\Column(length: 255)]
    private ?string $logo = null;

    #[ORM\Column]
    private ?int $maxLoanAmount = null;

    #[ORM\Column]
    private ?int $minLoanPeriodDays = null;

    #[ORM\Column]
    private ?int $maxLoanPeriodDays = null;

    #[ORM\Column]
    private ?float $interestRate = null;

    #[ORM\Column(length: 355)]
    private ?string $url = null;

    #[ORM\OneToMany(targetEntity: OfferCheckerRelation::class, mappedBy: 'offer')]
    private Collection $offerCheckerRelations;

    #[ORM\Column]
    private int $priority = 0;

    public function __construct()
    {
        $this->addedAt = new \DateTime();
        $this->offerCheckerRelations = new ArrayCollection();
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

    public function getAddedAt(): ?\DateTimeInterface
    {
        return $this->addedAt;
    }

    public function setAddedAt(\DateTimeInterface $addedAt): static
    {
        $this->addedAt = $addedAt;

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getMaxLoanAmount(): ?int
    {
        return $this->maxLoanAmount;
    }

    public function setMaxLoanAmount(int $maxLoanAmount): static
    {
        $this->maxLoanAmount = $maxLoanAmount;

        return $this;
    }

    public function getMinLoanPeriodDays(): ?int
    {
        return $this->minLoanPeriodDays;
    }

    public function setMinLoanPeriodDays(int $minLoanPeriodDays): static
    {
        $this->minLoanPeriodDays = $minLoanPeriodDays;

        return $this;
    }

    public function getMaxLoanPeriodDays(): ?int
    {
        return $this->maxLoanPeriodDays;
    }

    public function setMaxLoanPeriodDays(int $maxLoanPeriodDays): static
    {
        $this->maxLoanPeriodDays = $maxLoanPeriodDays;

        return $this;
    }

    public function getInterestRate(): ?float
    {
        return $this->interestRate;
    }

    public function setInterestRate(float $interestRate): static
    {
        $this->interestRate = $interestRate;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection<int, OfferCheckerRelation>
     */
    public function getOfferCheckerRelations(): Collection
    {
        return $this->offerCheckerRelations;
    }

    public function addOfferCheckerRelation(OfferCheckerRelation $offerCheckerRelation): static
    {
        if (!$this->offerCheckerRelations->contains($offerCheckerRelation)) {
            $this->offerCheckerRelations->add($offerCheckerRelation);
            $offerCheckerRelation->setOffer($this);
        }

        return $this;
    }

    public function removeOfferCheckerRelation(OfferCheckerRelation $offerCheckerRelation): static
    {
        if ($this->offerCheckerRelations->removeElement($offerCheckerRelation)) {
            // set the owning side to null (unless already changed)
            if ($offerCheckerRelation->getOffer() === $this) {
                $offerCheckerRelation->setOffer(null);
            }
        }

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): static
    {
        $this->priority = $priority;

        return $this;
    }
}

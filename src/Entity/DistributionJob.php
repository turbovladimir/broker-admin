<?php

namespace App\Entity;

use App\Repository\DistributionJobRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DistributionJobRepository::class)]
class DistributionJob
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $addedAt = null;

    #[ORM\OneToOne(inversedBy: 'distributionJob', cascade: ['persist', 'remove'])]
    private ?SmsQueue $queue = null;

    #[ORM\Column(nullable: true)]
    private ?array $settings = null;

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

    public function getQueue(): ?SmsQueue
    {
        return $this->queue;
    }

    public function setQueue(?SmsQueue $queue): static
    {
        $this->queue = $queue;

        return $this;
    }

    public function getSettings(): ?array
    {
        return $this->settings;
    }

    public function setSettings(?array $settings): static
    {
        $this->settings = $settings;

        return $this;
    }
}

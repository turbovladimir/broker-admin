<?php

namespace App\Entity;

use App\Repository\SmsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SmsRepository::class)]
class Sms
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $addedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $message = null;

    #[ORM\OneToOne(inversedBy: 'sms', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?SendingSmsJob $job = null;

    #[ORM\ManyToOne(inversedBy: 'smses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SmsQueue $smsQueue = null;

    public function __construct(string $message)
    {
        $this->addedAt = new \DateTime();
        $this->message = $message;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddedAt(): ?\DateTimeInterface
    {
        return $this->addedAt;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getJob(): ?SendingSmsJob
    {
        return $this->job;
    }

    public function setJob(SendingSmsJob $job): static
    {
        $this->job = $job;

        return $this;
    }

    public function getSmsQueue(): ?SmsQueue
    {
        return $this->smsQueue;
    }

    public function setSmsQueue(?SmsQueue $smsQueue): static
    {
        $this->smsQueue = $smsQueue;

        return $this;
    }
}

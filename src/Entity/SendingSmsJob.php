<?php

namespace App\Entity;

use App\Repository\SendingSmsJobRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

enum SendingJobStatus: string {
    case InQueue = 'in_queue';
    case Sent = 'sent';
    case Error = 'error';
}

#[ORM\Entity(repositoryClass: SendingSmsJobRepository::class)]
class SendingSmsJob
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $addedAt = null;

    #[ORM\ManyToOne(inversedBy: 'sendingSmsJobs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Contact $contact = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $sendingTime = null;

    #[ORM\Column(length: 10, enumType: SendingJobStatus::class)]
    private ?SendingJobStatus $status = SendingJobStatus::InQueue;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $errorText = null;

    #[ORM\OneToOne(mappedBy: 'job', cascade: ['persist', 'remove'])]
    private ?Sms $sms = null;

    #[ORM\ManyToOne(inversedBy: 'jobs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SmsQueue $smsQueue = null;

    public function __construct(\DateTime $time, SmsQueue $queue)
    {
        $this->addedAt = new \DateTime();
        $this->sendingTime = $time;
        $this->smsQueue = $queue;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddedAt(): ?\DateTimeInterface
    {
        return $this->addedAt;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    public function getSendingTime(): ?\DateTimeInterface
    {
        return $this->sendingTime;
    }

    public function getStatus(): ?SendingJobStatus
    {
        return $this->status;
    }

    public function setStatus(SendingJobStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getErrorText(): ?string
    {
        return $this->errorText;
    }

    public function setErrorText(?string $errorText): static
    {
        $this->errorText = $errorText;

        return $this;
    }

    public function getSms(): ?Sms
    {
        return $this->sms;
    }

    public function setSms(Sms $sms): static
    {
        // set the owning side of the relation if necessary
        if ($sms->getJob() !== $this) {
            $sms->setJob($this);
        }

        $this->sms = $sms;

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

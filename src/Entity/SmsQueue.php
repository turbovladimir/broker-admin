<?php

namespace App\Entity;

use App\Enums\QueueStatus;
use App\Repository\SmsQueueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;



#[ORM\Entity(repositoryClass: SmsQueueRepository::class)]
class SmsQueue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $addedAt = null;

    #[ORM\Column(length: 10)]
    private ?QueueStatus $status = QueueStatus::New;

    #[ORM\OneToMany(targetEntity: SendingSmsJob::class, mappedBy: 'smsQueue', cascade: ['persist'], orphanRemoval: true)]
    private Collection $jobs;

    #[ORM\OneToMany(targetEntity: Sms::class, mappedBy: 'smsQueue', cascade: ['persist'], orphanRemoval: true)]
    private Collection $smses;

    #[ORM\Column(length: 255)]
    private ?string $filePath = null;

    #[ORM\OneToMany(targetEntity: Contact::class, mappedBy: 'queue')]
    private Collection $contacts;

    #[ORM\OneToOne(mappedBy: 'queue', cascade: ['persist', 'remove'])]
    private ?DistributionJob $distributionJob = null;

    public function __construct()
    {
        $this->jobs = new ArrayCollection();
        $this->smses = new ArrayCollection();
        $this->addedAt = new \DateTime();
        $this->contacts = new ArrayCollection();
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

    public function getStatusValue(): string
    {
        return $this->status->value;
    }

    public function setStatus(QueueStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function isNew() : bool
    {
        return $this->status === QueueStatus::New;
    }

    /**
     * @return Collection<int, SendingSmsJob>
     */
    public function getJobs(): Collection
    {
        return $this->jobs;
    }
    public function addJob(SendingSmsJob $job): static
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs->add($job);
            $job->setSmsQueue($this);
        }

        return $this;
    }

    public function removeJob(SendingSmsJob $job): static
    {
        if ($this->jobs->removeElement($job)) {
            // set the owning side to null (unless already changed)
            if ($job->getSmsQueue() === $this) {
                $job->setSmsQueue(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sms>
     */
    public function getSmses(): Collection
    {
        return $this->smses;
    }

    public function addSmse(Sms $smse): static
    {
        if (!$this->smses->contains($smse)) {
            $this->smses->add($smse);
            $smse->setSmsQueue($this);
        }

        return $this;
    }

    public function removeSmse(Sms $smse): static
    {
        if ($this->smses->removeElement($smse)) {
            // set the owning side to null (unless already changed)
            if ($smse->getSmsQueue() === $this) {
                $smse->setSmsQueue(null);
            }
        }

        return $this;
    }

    public function getFileName() : ?string
    {
        return  basename($this->filePath);
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(string $filePath): static
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * @return Collection<int, Contact>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): static
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts->add($contact);
            $contact->setQueue($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): static
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getQueue() === $this) {
                $contact->setQueue(null);
            }
        }

        return $this;
    }

    public function getDistributionJob(): ?DistributionJob
    {
        return $this->distributionJob;
    }

    public function setDistributionJob(?DistributionJob $distributionJob): static
    {
        // unset the owning side of the relation if necessary
        if ($distributionJob === null && $this->distributionJob !== null) {
            $this->distributionJob->setQueue(null);
        }

        // set the owning side of the relation if necessary
        if ($distributionJob !== null && $distributionJob->getQueue() !== $this) {
            $distributionJob->setQueue($this);
        }

        $this->distributionJob = $distributionJob;

        return $this;
    }
}

<?php

namespace App\Entity;

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
    private ?string $status = null;

    #[ORM\OneToMany(targetEntity: SendingSmsJob::class, mappedBy: 'smsQueue', orphanRemoval: true)]
    private Collection $jobs;

    #[ORM\OneToMany(targetEntity: Contact::class, mappedBy: 'smsQueue')]
    private Collection $contacts;

    #[ORM\OneToMany(targetEntity: Sms::class, mappedBy: 'smsQueue', orphanRemoval: true)]
    private Collection $smses;

    public function __construct()
    {
        $this->jobs = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->smses = new ArrayCollection();
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
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
            $contact->setSmsQueue($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): static
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getSmsQueue() === $this) {
                $contact->setSmsQueue(null);
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
}

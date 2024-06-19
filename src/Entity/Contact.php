<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $addedAt = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 20)]
    private ?string $phone = null;

    #[ORM\Column(length: 50)]
    private ?string $contactId = null;

    #[ORM\OneToMany(targetEntity: SendingSmsJob::class, mappedBy: 'contact')]
    private Collection $sendingSmsJobs;

    #[ORM\ManyToOne(inversedBy: 'contacts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?SmsQueue $smsQueue = null;

    public function __construct()
    {
        $this->sendingSmsJobs = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getContactId(): ?string
    {
        return $this->contactId;
    }

    public function setContactId(string $contactId): static
    {
        $this->contactId = $contactId;

        return $this;
    }

    /**
     * @return Collection<int, SendingSmsJob>
     */
    public function getSendingSmsJobs(): Collection
    {
        return $this->sendingSmsJobs;
    }

    public function addSendingSmsJob(SendingSmsJob $sendingSmsJob): static
    {
        if (!$this->sendingSmsJobs->contains($sendingSmsJob)) {
            $this->sendingSmsJobs->add($sendingSmsJob);
            $sendingSmsJob->setContact($this);
        }

        return $this;
    }

    public function removeSendingSmsJob(SendingSmsJob $sendingSmsJob): static
    {
        if ($this->sendingSmsJobs->removeElement($sendingSmsJob)) {
            // set the owning side to null (unless already changed)
            if ($sendingSmsJob->getContact() === $this) {
                $sendingSmsJob->setContact(null);
            }
        }

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

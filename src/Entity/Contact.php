<?php

namespace App\Entity;

use App\Enums\ContactSource;
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

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 20)]
    private ?string $phone = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $contactId = null;

    #[ORM\OneToMany(targetEntity: SendingSmsJob::class, mappedBy: 'contact', cascade: ['persist'])]
    private Collection $sendingSmsJobs;

    #[ORM\ManyToOne(inversedBy: 'contacts')]
    private ?SmsQueue $queue = null;

    #[ORM\Column(length: 20)]
    private string $source = ContactSource::Direct->value;

    public function __construct(string $phone)
    {
        $this->setPhone($phone);
        $this->addedAt = new \DateTime();
        $this->sendingSmsJobs = new ArrayCollection();
        $this->generateContactId();
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

    private function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getContactId(): ?string
    {
        return $this->contactId;
    }

    public function generateContactId(): void
    {
        $contactId =    substr(md5($this->getPhone() . $this->addedAt->getTimestamp()), 0, 45);
        $this->contactId = $contactId;
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

    public function getQueue(): ?SmsQueue
    {
        return $this->queue;
    }

    public function setQueue(?SmsQueue $queue): static
    {
        $this->queue = $queue;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(ContactSource $source): static
    {
        $this->source = $source->value;

        return $this;
    }
}

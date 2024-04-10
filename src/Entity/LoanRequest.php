<?php

namespace App\Entity;

use App\Repository\LoanRequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LoanRequestRepository::class)]
class LoanRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $addedAt = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $surname = null;

    #[ORM\Column(length: 255)]
    private ?string $patron = null;

    #[ORM\Column(length: 20)]
    private ?string $phone = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birth = null;

    #[ORM\Column(length: 30)]
    private ?string $email = null;

    #[ORM\Column]
    private ?int $passportSeries = null;

    #[ORM\Column]
    private ?int $passportNumber = null;

    #[ORM\Column]
    private ?int $departmentCode = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $issueDate = null;

    #[ORM\Column(length: 255)]
    private ?string $department = null;

    #[ORM\Column(length: 255)]
    private ?string $regPlace = null;

    #[ORM\Column(length: 100)]
    private ?string $birthPlace = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param \DateTimeInterface|null $addedAt
     */
    public function setAddedAt(?\DateTimeInterface $addedAt): void
    {
        $this->addedAt = $addedAt;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getAddedAt(): ?\DateTimeInterface
    {
        return $this->addedAt;
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPatron(): ?string
    {
        return $this->patron;
    }

    public function setPatron(string $patron): static
    {
        $this->patron = $patron;

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

    public function getBirth(): ?\DateTimeInterface
    {
        return $this->birth;
    }

    public function setBirth(string $birth): static
    {
        $this->birth = new \DateTime($birth);

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassportSeries(): ?int
    {
        return $this->passportSeries;
    }

    public function setPassportSeries(int $passportSeries): static
    {
        $this->passportSeries = $passportSeries;

        return $this;
    }

    public function getPassportNumber(): ?int
    {
        return $this->passportNumber;
    }

    public function setPassportNumber(int $passportNumber): static
    {
        $this->passportNumber = $passportNumber;

        return $this;
    }

    public function getDepartmentCode(): ?int
    {
        return $this->departmentCode;
    }

    public function setDepartmentCode($departmentCode): static
    {
        $this->departmentCode = (int)str_replace('-', '', $departmentCode);

        return $this;
    }

    public function getIssueDate(): ?\DateTimeInterface
    {
        return $this->issueDate;
    }

    public function setIssueDate(string $issueDate): static
    {
        $this->issueDate = new \DateTime($issueDate);

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(string $department): static
    {
        $this->department = $department;

        return $this;
    }

    public function getRegPlace(): ?string
    {
        return $this->regPlace;
    }

    public function setRegPlace(string $regPlace): static
    {
        $this->regPlace = $regPlace;

        return $this;
    }

    public function getBirthPlace(): ?string
    {
        return $this->birthPlace;
    }

    public function setBirthPlace(string $birthPlace): static
    {
        $this->birthPlace = $birthPlace;

        return $this;
    }
}

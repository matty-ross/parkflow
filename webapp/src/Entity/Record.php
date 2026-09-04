<?php

namespace App\Entity;

use App\Repository\RecordRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecordRepository::class)]
#[ORM\Table('records')]
class Record
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 20, index: true)]
    private ?string $licensePlate = null;

    #[ORM\ManyToOne(inversedBy: 'records')]
    private ?User $recognizedUser = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, index: true)]
    private ?\DateTime $enteredAt = null;

    #[ORM\Column(type: Types::STRING, length: 36, unique: true, nullable: true)]
    private ?string $entrySnapshotUuid = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true, index: true)]
    private ?\DateTime $exitedAt = null;

    #[ORM\Column(type: Types::STRING, length: 36, unique: true, nullable: true)]
    private ?string $exitSnapshotUuid = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, index: true)]
    private ?\DateTime $freeUntil = null;

    /**
     * @var Collection<int, Payment>
     */
    #[ORM\OneToMany(targetEntity: Payment::class, mappedBy: 'record', orphanRemoval: true)]
    private Collection $payments;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLicensePlate(): ?string
    {
        return $this->licensePlate;
    }

    public function setLicensePlate(?string $licensePlate): static
    {
        $this->licensePlate = $licensePlate;

        return $this;
    }

    public function getRecognizedUser(): ?User
    {
        return $this->recognizedUser;
    }

    public function setRecognizedUser(?User $recognizedUser): static
    {
        $this->recognizedUser = $recognizedUser;

        return $this;
    }

    public function getEnteredAt(): ?\DateTime
    {
        return $this->enteredAt;
    }

    public function setEnteredAt(?\DateTime $enteredAt): static
    {
        $this->enteredAt = $enteredAt;

        return $this;
    }

    public function getEntrySnapshotUuid(): ?string
    {
        return $this->entrySnapshotUuid;
    }

    public function setEntrySnapshotUuid(?string $entrySnapshotUuid): static
    {
        $this->entrySnapshotUuid = $entrySnapshotUuid;

        return $this;
    }

    public function getExitedAt(): ?\DateTime
    {
        return $this->exitedAt;
    }

    public function setExitedAt(?\DateTime $exitedAt): static
    {
        $this->exitedAt = $exitedAt;

        return $this;
    }

    public function getExitSnapshotUuid(): ?string
    {
        return $this->exitSnapshotUuid;
    }

    public function setExitSnapshotUuid(?string $exitSnapshotUuid): static
    {
        $this->exitSnapshotUuid = $exitSnapshotUuid;

        return $this;
    }

    public function getFreeUntil(): ?\DateTime
    {
        return $this->freeUntil;
    }

    public function setFreeUntil(?\DateTime $freeUntil): static
    {
        $this->freeUntil = $freeUntil;

        return $this;
    }

    /**
     * @return Collection<int, Payment>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): static
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setRecord($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getRecord() === $this) {
                $payment->setRecord(null);
            }
        }

        return $this;
    }
}

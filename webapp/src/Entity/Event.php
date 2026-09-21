<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ORM\Table('events')]
#[ORM\HasLifecycleCallbacks]
class Event
{
    final public const ACTION_ENTRY = 'ACTION_ENTRY';
    final public const ACTION_EXIT = 'ACTION_EXIT';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, index: true)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(type: Types::STRING, length: 255, index: true)]
    #[Assert\NotBlank]
    #[Assert\Choice(choices: [self::ACTION_ENTRY, self::ACTION_EXIT])]
    private ?string $action = null;

    #[ORM\Column(type: Types::STRING, length: 20, index: true)]
    #[Assert\NotBlank]
    private ?string $licensePlate = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    private ?User $recognizedUser = null;

    #[ORM\Column(type: Types::STRING, length: 36, unique: true, nullable: true)]
    private ?string $snapshotUuid = null;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(?string $action): static
    {
        $this->action = $action;

        return $this;
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

    public function getSnapshotUuid(): ?string
    {
        return $this->snapshotUuid;
    }

    public function setSnapshotUuid(?string $snapshotUuid): static
    {
        $this->snapshotUuid = $snapshotUuid;

        return $this;
    }
}

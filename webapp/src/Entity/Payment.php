<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
#[ORM\Table('payments')]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, index: true)]
    private ?\DateTime $paidAt = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Record $record = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amountExcludingVat = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amountIncludingVat = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $vatRate = null;

    #[ORM\Column(type: Types::STRING, length: 3)]
    private ?string $currencyCode = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?\DateTime $oldRecordFreeUntil = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?\DateTime $newRecordFreeUntil = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaidAt(): ?\DateTime
    {
        return $this->paidAt;
    }

    public function setPaidAt(?\DateTime $paidAt): static
    {
        $this->paidAt = $paidAt;

        return $this;
    }

    public function getRecord(): ?Record
    {
        return $this->record;
    }

    public function setRecord(?Record $record): static
    {
        $this->record = $record;

        return $this;
    }

    public function getAmountExcludingVat(): ?string
    {
        return $this->amountExcludingVat;
    }

    public function setAmountExcludingVat(?string $amountExcludingVat): static
    {
        $this->amountExcludingVat = $amountExcludingVat;

        return $this;
    }

    public function getAmountIncludingVat(): ?string
    {
        return $this->amountIncludingVat;
    }

    public function setAmountIncludingVat(?string $amountIncludingVat): static
    {
        $this->amountIncludingVat = $amountIncludingVat;

        return $this;
    }

    public function getVatRate(): ?string
    {
        return $this->vatRate;
    }

    public function setVatRate(?string $vatRate): static
    {
        $this->vatRate = $vatRate;

        return $this;
    }

    public function getCurrencyCode(): ?string
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode(?string $currencyCode): static
    {
        $this->currencyCode = $currencyCode;

        return $this;
    }

    public function getOldRecordFreeUntil(): ?\DateTime
    {
        return $this->oldRecordFreeUntil;
    }

    public function setOldRecordFreeUntil(?\DateTime $oldRecordFreeUntil): static
    {
        $this->oldRecordFreeUntil = $oldRecordFreeUntil;

        return $this;
    }

    public function getNewRecordFreeUntil(): ?\DateTime
    {
        return $this->newRecordFreeUntil;
    }

    public function setNewRecordFreeUntil(?\DateTime $newRecordFreeUntil): static
    {
        $this->newRecordFreeUntil = $newRecordFreeUntil;

        return $this;
    }
}

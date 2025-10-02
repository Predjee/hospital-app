<?php

declare(strict_types=1);

namespace App\Treatment\Domain\Entity;

use App\Patient\Domain\Entity\Patient;
use App\Treatment\Domain\Enum\TreatmentStatus;
use App\Treatment\Domain\Enum\TreatmentType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Table(name: 'treatment')]
class Treatment
{
    #[ORM\Id]
    #[ORM\Column(type: 'ulid', unique: true)]
    private Ulid $id;

    #[ORM\ManyToOne(targetEntity: Patient::class, inversedBy: 'treatments')]
    #[ORM\JoinColumn(name: 'patient_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Patient $patient;

    #[ORM\Column(enumType: TreatmentType::class)]
    private TreatmentType $type;

    #[ORM\Column(enumType: TreatmentStatus::class)]
    private TreatmentStatus $status = TreatmentStatus::PLANNED;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $completedAt = null;

    public function __construct(Patient $patient, TreatmentType $type, TreatmentStatus $status = TreatmentStatus::PLANNED)
    {
        $this->id = new Ulid();
        $this->patient = $patient;
        $this->type = $type;
        $this->status = $status;
    }

    public function id(): Ulid
    {
        return $this->id;
    }

    public function patient(): Patient
    {
        return $this->patient;
    }

    public function type(): TreatmentType
    {
        return $this->type;
    }

    public function status(): TreatmentStatus
    {
        return $this->status;
    }

    public function startedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function completedAt(): ?\DateTimeImmutable
    {
        return $this->completedAt;
    }

    public function isCompleted(): bool
    {
        return TreatmentStatus::COMPLETED === $this->status;
    }

    public function start(): void
    {
        if (TreatmentStatus::PLANNED !== $this->status) {
            return;
        }

        $this->status = TreatmentStatus::STARTED;
        $this->startedAt = new \DateTimeImmutable();
    }

    public function complete(): void
    {
        if (TreatmentStatus::COMPLETED === $this->status) {
            return;
        }

        $this->status = TreatmentStatus::COMPLETED;
        $this->completedAt = new \DateTimeImmutable();
    }

    public function cancel(): void
    {
        if (TreatmentStatus::COMPLETED === $this->status) {
            return;
        }

        $this->status = TreatmentStatus::CANCELED;
    }

    public function durationInMinutes(): ?int
    {
        if (!$this->startedAt || !$this->completedAt) {
            return null;
        }

        return $this->completedAt->diff($this->startedAt)->i
            + 60 * $this->completedAt->diff($this->startedAt)->h
            + 1440 * $this->completedAt->diff($this->startedAt)->d;
    }

    public function markAsCompleted(): void
    {
        $this->status = TreatmentStatus::COMPLETED;
    }
}

<?php

declare(strict_types=1);

namespace App\Patient\Domain\Entity;

use App\Patient\Domain\Enum\PatientStatus;
use App\Treatment\Domain\Entity\Treatment;
use App\Treatment\Domain\Enum\TreatmentType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

#[ORM\Entity]
#[ORM\Table(name: 'patient')]
class Patient
{
    #[ORM\Id]
    #[ORM\Column(type: 'ulid', unique: true)]
    private Ulid $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $birthDate;

    #[ORM\Column(enumType: PatientStatus::class)]
    private PatientStatus $status = PatientStatus::ADMITTED;

    /** @var Collection<int, Treatment> */
    #[ORM\OneToMany(
        targetEntity: Treatment::class,
        mappedBy: 'patient',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $treatments;

    public function __construct(string $name, \DateTimeImmutable $birthDate, PatientStatus $status = PatientStatus::ADMITTED)
    {
        $this->id = new Ulid();
        $this->name = trim($name);
        $this->birthDate = $birthDate;
        $this->status = $status;
        $this->treatments = new ArrayCollection();
    }

    public function id(): Ulid
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function status(): PatientStatus
    {
        return $this->status;
    }

    public function birthDate(): ?\DateTimeImmutable
    {
        return $this->birthDate;
    }

    /** @return Collection<int, Treatment> */
    public function treatments(): Collection
    {
        return $this->treatments;
    }

    public function age(): int
    {
        $now = new \DateTimeImmutable();
        $age = $now->diff($this->birthDate)->y;

        return max($age, 0);
    }

    public function rename(string $newName): void
    {
        $this->name = trim($newName);
    }

    public function admit(): void
    {
        $this->status = PatientStatus::ADMITTED;
    }

    public function discharge(): void
    {
        $this->status = PatientStatus::DISCHARGED;
    }

    public function dischargeIfNoPendingTreatments(): void
    {
        if (0 === \count($this->pendingTreatments())) {
            $this->discharge();
        }
    }

    public function scheduleTreatment(TreatmentType $type): Treatment
    {
        $treatment = new Treatment($this, $type);
        $this->addTreatment($treatment);

        return $treatment;
    }

    public function addTreatment(Treatment $treatment): void
    {
        if (!$this->treatments->contains($treatment)) {
            $this->treatments->add($treatment);
        }
    }

    public function removeTreatment(Treatment $treatment): void
    {
        $this->treatments->removeElement($treatment);
    }

    public function findTreatmentByType(TreatmentType $type): ?Treatment
    {
        foreach ($this->treatments as $t) {
            if ($t->type() === $type) {
                return $t;
            }
        }

        return null;
    }

    /** @return Treatment[] */
    public function pendingTreatments(): array
    {
        return $this->treatments
            ->filter(fn (Treatment $t) => !$t->isCompleted())
            ->toArray();
    }

    public function hasPendingTreatment(TreatmentType $type): bool
    {
        return $this->treatments->exists(
            fn ($k, Treatment $t) => !$t->isCompleted() && $t->type() === $type
        );
    }

    public function markAsDone(Treatment $treatment): void
    {
        $treatment->complete();
    }
}

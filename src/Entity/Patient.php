<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\PatientStatus;
use App\Enum\TreatmentType;
use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
#[ORM\Table(name: 'patient')]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /** @var Collection<int, Treatment> */
    #[ORM\OneToMany(targetEntity: Treatment::class, mappedBy: 'patient', cascade: ['persist', 'remove'])]
    private Collection $treatments {
        get {
            return $this->treatments;
        }
    }

    public function __construct(
        #[ORM\Column(length: 255)]
        public string $name,

        #[ORM\Column(enumType: PatientStatus::class)]
        public PatientStatus $status = PatientStatus::ADMITTED,

        #[ORM\Column(type: 'datetime_immutable', nullable: true)]
        public ?\DateTimeImmutable $birthDate = null,
    ) {
        $this->treatments = new ArrayCollection();
    }

    public int $age {
        get => $this->birthDate
            ? $this->birthDate->diff(new \DateTimeImmutable())->y
            : 0;
    }

    public function addTreatment(Treatment $treatment): self
    {
        if (!$this->treatments->contains($treatment)) {
            $this->treatments->add($treatment);
            if ($treatment->patient !== $this) {
                throw new \LogicException('Treatment hoort al bij deze patiënt gemaakt te worden.');
            }
        }

        return $this;
    }

    public function removeTreatment(Treatment $treatment): self
    {
        $this->treatments->removeElement($treatment);

        return $this;
    }

    public function findTreatmentByType(TreatmentType $type): ?Treatment
    {
        foreach ($this->treatments as $treatment) {
            if ($treatment->type === $type) {
                return $treatment;
            }
        }

        return null;
    }

    /**
     * @return Treatment[]
     */
    public function getPendingTreatments(): array
    {
        return $this->treatments->filter(fn (Treatment $t) => !$t->completed)->toArray();
    }

    public function hasPendingTreatment(TreatmentType $type): bool
    {
        return array_any($this->getPendingTreatments(), fn ($treatment) => $treatment->type === $type);
    }

    public function dischargeIfNoPendingTreatments(): void
    {
        if ([] === $this->getPendingTreatments()) {
            $this->status = PatientStatus::DISCHARGED;
        }
    }

    public function discharge(): void
    {
        $this->status = PatientStatus::DISCHARGED;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}

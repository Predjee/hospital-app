<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\PatientStatus;
use App\Repository\PatientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
#[ORM\Table(name: 'patient')]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    /** @var int|null $id */
    private ?int $id = null {
        get => $this->id;
    }

    public function __construct(
        #[ORM\Column(length: 255)]
        public string $name,

        #[ORM\Column(enumType: PatientStatus::class)]
        public PatientStatus $status = PatientStatus::ADMITTED,

        #[ORM\Column(type: 'datetime_immutable', nullable: true)]
        public ?\DateTimeImmutable $birthDate = null,
    ) {
    }

    public int $age {
        get => $this->birthDate
            ? $this->birthDate->diff(new \DateTimeImmutable())->y
            : 0;
    }

    public function discharge(): void
    {
        $this->status = PatientStatus::DISCHARGED;
    }
}

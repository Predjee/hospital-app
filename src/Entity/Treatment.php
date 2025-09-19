<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\TreatmentType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'treatment')]
class Treatment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null {
        get => $this->id;
    }

    public function __construct(
        #[ORM\ManyToOne(targetEntity: Patient::class, inversedBy: 'treatments')]
        public readonly Patient $patient,

        #[ORM\Column(enumType: TreatmentType::class)]
        public TreatmentType $type,

        #[ORM\Column(type: 'boolean')]
        public bool $completed = false,
    ) {
    }

    public function complete(): void
    {
        $this->completed = true;
    }
}

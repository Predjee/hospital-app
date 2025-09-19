<?php

declare(strict_types=1);

namespace App\DTO;

use App\Enum\PatientStatus;

readonly class PatientSummary
{
    public int $age;

    /**
     * @param TreatmentSummary[] $treatments
     */
    public function __construct(
        public int $id,
        public string $name,
        public PatientStatus $status,
        public ?\DateTimeImmutable $birthDate,
        public array $treatments = [],
    ) {
        $this->age = $birthDate
            ? $birthDate->diff(new \DateTimeImmutable())->y
            : 0;
    }
}

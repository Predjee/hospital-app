<?php

declare(strict_types=1);

namespace App\Patient\Application\DTO;

use App\Patient\Domain\Enum\PatientStatus;
use App\Treatment\Application\DTO\TreatmentSummaryData;
use Symfony\Component\Uid\Ulid;

readonly class PatientSummaryData
{
    /**
     * @param TreatmentSummaryData[] $treatments
     */
    public function __construct(
        public Ulid $id,
        public string $name,
        public PatientStatus $status,
        public int $age,
        public array $treatments = [],
    ) {
    }
}

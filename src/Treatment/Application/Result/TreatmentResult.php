<?php

declare(strict_types=1);

namespace App\Treatment\Application\Result;

use App\Patient\Application\DTO\PatientSummaryData;

final readonly class TreatmentResult
{
    public function __construct(
        public string $message,
        public string $type,
        public ?PatientSummaryData $patientSummary,
    ) {
    }
}

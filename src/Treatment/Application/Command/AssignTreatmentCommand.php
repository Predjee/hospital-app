<?php

declare(strict_types=1);

namespace App\Treatment\Application\Command;

use App\Treatment\Domain\Enum\TreatmentType;
use Symfony\Component\Messenger\Attribute\AsMessage;
use Symfony\Component\Uid\Ulid;

#[AsMessage]
readonly class AssignTreatmentCommand
{
    public function __construct(
        public Ulid $patientId,
        public TreatmentType $treatmentType,
    ) {
    }
}

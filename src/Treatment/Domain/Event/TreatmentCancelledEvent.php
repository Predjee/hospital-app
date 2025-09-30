<?php

declare(strict_types=1);

namespace App\Treatment\Domain\Event;

use Symfony\Component\Uid\Ulid;

final readonly class TreatmentCancelledEvent
{
    public function __construct(
        public Ulid $treatmentId,
        public Ulid $patientId,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace App\Treatment\Domain\Event;

use Symfony\Component\Uid\Ulid;

final readonly class TreatmentCompletedEvent
{
    public function __construct(
        public Ulid $treatmentId,
        public Ulid $patientId,
        public \DateTimeImmutable $completedAt,
    ) {
    }
}

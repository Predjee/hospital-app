<?php

declare(strict_types=1);

namespace App\Patient\Domain\Event;

use Symfony\Component\Uid\Ulid;

final readonly class PatientAdmittedEvent
{
    public function __construct(public Ulid $patientId)
    {
    }
}

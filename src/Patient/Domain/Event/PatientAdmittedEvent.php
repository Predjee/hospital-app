<?php

declare(strict_types=1);

namespace App\Patient\Domain\Event;

use App\Patient\Domain\Entity\Patient;

final readonly class PatientAdmittedEvent
{
    public function __construct(public Patient $patient)
    {
    }
}

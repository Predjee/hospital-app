<?php

declare(strict_types=1);

namespace App\Treatment\Application\Strategy;

use App\Patient\Domain\Entity\Patient;
use App\Treatment\Domain\Enum\TreatmentType;

interface TreatmentStrategy
{
    public function supports(Patient $patient, TreatmentType $type): bool;

    public function treat(Patient $patient): string;
}

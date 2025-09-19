<?php

declare(strict_types=1);

namespace App\Treatment;

use App\Entity\Patient;
use App\Enum\TreatmentType;

interface TreatmentStrategy
{
    public function supports(Patient $patient, TreatmentType $type): bool;

    public function treat(Patient $patient): string;
}

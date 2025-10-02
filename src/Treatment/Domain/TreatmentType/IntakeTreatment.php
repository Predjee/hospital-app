<?php

declare(strict_types=1);

namespace App\Treatment\Domain\TreatmentType;

use App\Patient\Domain\Entity\Patient;
use App\Treatment\Application\Strategy\TreatmentStrategy;
use App\Treatment\Domain\Enum\TreatmentType;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.treatment_strategy')]
class IntakeTreatment implements TreatmentStrategy
{
    public function supports(Patient $patient, TreatmentType $type): bool
    {
        return TreatmentType::INTAKE === $type && $patient->hasPendingTreatment($type);
    }

    public function treat(Patient $patient): string
    {
        $treatment = $patient->findTreatmentByType(TreatmentType::INTAKE);

        if (!$treatment) {
            throw new \LogicException('Geen intake gevonden.');
        }

        return "Goeiedag! {$patient->name()} wordt opgenomen voor een intake.";
    }
}
